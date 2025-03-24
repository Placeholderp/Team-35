import { ref, computed } from 'vue';
import { v4 as uuidv4 } from 'uuid';

export function useOptimisticMutation(options) {
  const {
    mutationFn,
    onMutate,
    onSuccess,
    onError,
    onSettled
  } = options;
  
  const isLoading = ref(false);
  const isError = ref(false);
  const error = ref(null);
  const pendingMutations = ref(new Map());
  
  // Track whether any mutations are in progress
  const isAnyMutationPending = computed(() => pendingMutations.value.size > 0);
  
  // Perform mutation with optimistic update
  async function mutate(variables, callbacks = {}) {
    const mutationId = uuidv4();
    isLoading.value = true;
    isError.value = false;
    error.value = null;
    
    // Store pending mutation
    pendingMutations.value.set(mutationId, {
      variables,
      callbacks,
      status: 'pending'
    });
    
    let previousData = null;
    let context = {};
    
    try {
      // Perform optimistic update if provided
      if (onMutate) {
        const mutateResult = await onMutate(variables);
        previousData = mutateResult?.previousData;
        context = mutateResult?.context || {};
      }
      
      // Call mutation function
      const result = await mutationFn(variables);
      
      // Update pending mutation status
      pendingMutations.value.set(mutationId, {
        ...pendingMutations.value.get(mutationId),
        status: 'success',
        result
      });
      
      // Call onSuccess callback
      if (onSuccess) {
        await onSuccess(result, variables, context);
      }
      
      // Call component-specific onSuccess callback
      if (callbacks.onSuccess) {
        await callbacks.onSuccess(result, variables, context);
      }
      
      return result;
    } catch (err) {
      // Update pending mutation status
      pendingMutations.value.set(mutationId, {
        ...pendingMutations.value.get(mutationId),
        status: 'error',
        error: err
      });
      
      isError.value = true;
      error.value = err;
      
      // Call onError callback to revert optimistic update
      if (onError) {
        await onError(err, variables, context, previousData);
      }
      
      // Call component-specific onError callback
      if (callbacks.onError) {
        await callbacks.onError(err, variables, context, previousData);
      }
      
      throw err;
    } finally {
      // Update loading state if no pending mutations
      if (pendingMutations.value.size === 1) {
        isLoading.value = false;
      }
      
      // Call onSettled callback
      if (onSettled) {
        await onSettled(variables, context);
      }
      
      // Call component-specific onSettled callback
      if (callbacks.onSettled) {
        await callbacks.onSettled(variables, context);
      }
      
      // Remove pending mutation after a delay to ensure UI has time to update
      setTimeout(() => {
        pendingMutations.value.delete(mutationId);
        
        // Update loading state if no pending mutations
        if (pendingMutations.value.size === 0) {
          isLoading.value = false;
        }
      }, 300);
    }
  }
  
  return {
    mutate,
    isLoading,
    isError,
    error,
    pendingMutations,
    isAnyMutationPending
  };
}