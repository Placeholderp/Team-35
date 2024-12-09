document.getElementById('calculateBtn').addEventListener('click', calculateCalories);
document.getElementById('resetBtn').addEventListener('click', resetForm);

function calculateCalories() {
  const gender = document.getElementById('gender').value;
  const age = parseInt(document.getElementById('age').value);
  const weight = parseFloat(document.getElementById('weight').value);
  const height = parseFloat(document.getElementById('height').value);
  const activity = document.getElementById('activity').value;
  const goal = document.getElementById('goal').value;

  const resultBox = document.getElementById('resultBox');

  // This is for input validation
  if (!gender || isNaN(age) || isNaN(weight) || isNaN(height) || !activity || !goal) {
    resultBox.value = 'Please fill all fields correctly.';
    return;
  }

  if (age > 100 || weight > 170 || height > 240) { // This will ensure that the user cannot input any unrealistic details
    resultBox.value = 'Please fill all fields appropriately.';
    return;
  }

  // Calculate Basal Metabolic Rate (BMR)
  let bmr;
  if (gender === 'male') {
    bmr = 10 * weight + 6.25 * height - 5 * age + 5;
  } else if (gender === 'female') {
    bmr = 10 * weight + 6.25 * height - 5 * age - 161;
  }

  // Adjust BMR for activity level
  let activityMultiplier = 1;
  if (activity === 'low') activityMultiplier = 1.2;
  if (activity === 'intermediate') activityMultiplier = 1.55;
  if (activity === 'high') activityMultiplier = 1.725;

  let calories = bmr * activityMultiplier;

  // Adjust for goal
  if (goal === 'lose') calories -= 500; // Reduces calories for weight loss
  if (goal === 'gain') calories += 500; // Adds calories for muscle gain

  // Display the result here
  resultBox.value = `${Math.round(calories)} calories/day.`;
}

function resetForm() {
  document.getElementById('resultBox').value = '';
}
