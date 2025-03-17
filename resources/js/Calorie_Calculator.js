
// Wait for DOM to be loaded
document.addEventListener("DOMContentLoaded", function() {
  document.getElementById("calculateBtn")
          .addEventListener("click", calculateCalories);
  document.getElementById("resetBtn")
          .addEventListener("click", resetForm);
});

function calculateCalories() {
  const gender = document.getElementById("gender").value;
  const age = parseInt(document.getElementById("age").value);
  const weight = parseFloat(document.getElementById("weight").value);
  const height = parseFloat(document.getElementById("height").value);
  const activity = document.getElementById("activity").value;
  const goal = document.getElementById("goal").value;
  const resultBox = document.getElementById("resultBox");

  // Input validation
  if (!gender || isNaN(age) || isNaN(weight) || isNaN(height) ||
      !activity || !goal) {
    resultBox.value = "Please fill all fields correctly.";
    return;
  }

  // Extra constraints
  if (age > 100 || weight > 170 || height > 240) {
    resultBox.value = "Please fill all fields appropriately.";
    return;
  }

  // Calculate BMR
  let bmr;
  if (gender === "male") {
    bmr = 10 * weight + 6.25 * height - 5 * age + 5;
  } else {
    bmr = 10 * weight + 6.25 * height - 5 * age - 161;
  }

  // Adjust BMR for activity level
  let activityMultiplier = 1;
  if (activity === "low") activityMultiplier = 1.2;
  if (activity === "intermediate") activityMultiplier = 1.55;
  if (activity === "high") activityMultiplier = 1.725;

  let calories = bmr * activityMultiplier;

  // Adjust for goal
  if (goal === "lose") calories -= 500;
  if (goal === "gain") calories += 500;

  // Display result
  resultBox.value = `${Math.round(calories)} calories/day.`;
}

function resetForm() {
  // Clear result box
  document.getElementById("resultBox").value = "";
}