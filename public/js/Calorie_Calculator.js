// Calorie Calculator JavaScript
document.addEventListener("DOMContentLoaded", function() {
  console.log("DOM loaded - attaching event listeners");
  
  // Get the calculate button and add event listener
  const calculateBtn = document.getElementById("calculateBtn");
  if (calculateBtn) {
    calculateBtn.addEventListener("click", calculateCalories);
    console.log("Calculate button listener added");
  } else {
    console.error("Calculate button not found");
  }
  
  // Get the reset button and add event listener
  const resetBtn = document.getElementById("resetBtn");
  if (resetBtn) {
    resetBtn.addEventListener("click", resetForm);
    console.log("Reset button listener added");
  } else {
    console.error("Reset button not found");
  }

  // Initially hide any Alpine.js related errors that might occur
  window.addEventListener('error', function(e) {
    if (e.message.includes('Alpine') || e.message.includes('unexpected token')) {
      console.warn('Alpine.js related error detected:', e.message);
    }
  });
});

function calculateCalories() {
  console.log("Calculate function called");
  
  try {
    // Get form values with validation
    const genderElement = document.getElementById("gender");
    const ageElement = document.getElementById("age");
    const weightElement = document.getElementById("weight");
    const heightElement = document.getElementById("height");
    const activityElement = document.getElementById("activity");
    const goalElement = document.getElementById("goal");
    const resultBox = document.getElementById("resultBox");
    
    // Check if elements exist
    if (!genderElement || !ageElement || !weightElement || !heightElement || 
        !activityElement || !goalElement || !resultBox) {
      console.error("One or more form elements not found");
      alert("Form error: Some form elements could not be found. Please refresh the page and try again.");
      return;
    }
    
    const gender = genderElement.value;
    const age = parseInt(ageElement.value);
    const weight = parseFloat(weightElement.value);
    const height = parseFloat(heightElement.value);
    const activity = activityElement.value;
    const goal = goalElement.value;

    console.log("Form values:", { gender, age, weight, height, activity, goal });

    // Input validation
    if (!gender || isNaN(age) || isNaN(weight) || isNaN(height) ||
        !activity || !goal) {
      resultBox.value = "Please fill all fields correctly.";
      console.error("Validation failed - empty fields or invalid values");
      return;
    }

    // Extra constraints
    if (age < 15 || age > 100) {
      resultBox.value = "Please enter an age between 15 and 100.";
      return;
    }
    
    if (weight < 30 || weight > 170) {
      resultBox.value = "Please enter a weight between 30 and 170 kg.";
      return;
    }
    
    if (height < 100 || height > 240) {
      resultBox.value = "Please enter a height between 100 and 240 cm.";
      return;
    }

    // Calculate BMR using Mifflin-St Jeor Equation
    let bmr;
    if (gender === "male") {
      bmr = 10 * weight + 6.25 * height - 5 * age + 5;
    } else {
      bmr = 10 * weight + 6.25 * height - 5 * age - 161;
    }
    console.log("BMR calculated:", bmr);

    // Adjust BMR for activity level
    let activityMultiplier = 1;
    switch(activity) {
      case "low":
        activityMultiplier = 1.2;
        break;
      case "intermediate":
        activityMultiplier = 1.55;
        break;
      case "high":
        activityMultiplier = 1.725;
        break;
      default:
        activityMultiplier = 1.2;
    }
    console.log("Activity multiplier:", activityMultiplier);

    let calories = bmr * activityMultiplier;
    console.log("Base calories after activity adjustment:", calories);

    // Adjust for goal
    switch(goal) {
      case "lose":
        calories -= 500;
        break;
      case "gain":
        calories += 500;
        break;
      // maintain stays the same
    }
    console.log("Final calories after goal adjustment:", calories);

    // Round to nearest whole number
    calories = Math.round(calories);

    // Display result
    resultBox.value = `${calories} calories/day`;
    
    // Show results container if it exists
    const resultsContainer = document.getElementById("results-container");
    if (resultsContainer) {
      resultsContainer.classList.add("show");
    }
    
  } catch (error) {
    console.error("Error in calculation:", error);
    const resultBox = document.getElementById("resultBox");
    if (resultBox) {
      resultBox.value = "An error occurred during calculation.";
    }
    alert("Something went wrong with the calculation. Please try again.");
  }
}

function resetForm() {
  console.log("Form reset called");
  
  try {
    // Clear result box
    const resultBox = document.getElementById("resultBox");
    if (resultBox) {
      resultBox.value = "";
      console.log("Result box cleared");
    } else {
      console.error("Result box not found");
    }
    
    // Hide results container if it exists
    const resultsContainer = document.getElementById("results-container");
    if (resultsContainer) {
      resultsContainer.classList.remove("show");
    }
    
    // Reset all form fields
    const form = document.getElementById("calorieForm");
    if (form) {
      form.reset();
    }
  } catch (error) {
    console.error("Error resetting form:", error);
  }
}