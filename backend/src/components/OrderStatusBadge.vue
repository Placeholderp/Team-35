<template>
    <span 
      :class="[
        'inline-flex items-center px-2 py-1 text-xs font-medium rounded-full',
        statusClass
      ]"
    >
      {{ statusLabel }}
    </span>
  </template>
  
  <script>
  export default {
    name: 'OrderStatusBadge',
    props: {
      status: {
        type: String,
        required: true,
        validator: (value) => ['pending', 'processing', 'shipped', 'delivered', 'paid', 'cancelled', 'refunded'].includes(value)
      }
    },
    computed: {
      statusClass() {
        switch (this.status.toLowerCase()) {
          case 'pending':
            return 'bg-yellow-100 text-yellow-800';
          case 'processing':
            return 'bg-blue-100 text-blue-800';
          case 'shipped':
            return 'bg-indigo-100 text-indigo-800';
          case 'delivered':
            return 'bg-green-100 text-green-800';
          case 'paid':
            return 'bg-green-100 text-green-800';
          case 'cancelled':
            return 'bg-red-100 text-red-800';
          case 'refunded':
            return 'bg-gray-100 text-gray-800';
          default:
            return 'bg-gray-100 text-gray-800';
        }
      },
      statusLabel() {
        return this.status.charAt(0).toUpperCase() + this.status.slice(1);
      }
    }
  };
  </script>