<!--********************************
Developer: Esraa Mubarak
University ID: 230087321
Function: This page is to display all the products/services we are selling
********************************/-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Store - Product Page</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Charis+SIL:ital,wght@0,400;0,700;1,400;1,700&family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>
<body>
    <!--Header Section-->
    <header>
        <div class="logo">Aston-35 Fitness</div>
        <nav>
         <a href="#home">Home</a>
        <a href="#shop">Shop</a>
         <a href="#workouts" class="active">Workout Plans</a> 
        <a href="#contact">Contact</a>
         <a href="#cart" class="cart-icon">🛒</a>
        </nav>
    </header>

    <!-- Product Page Content -->
    <main>
        <h1>Explore Our Workouts and Supplements!</h1>

        <!-- Workout Plans Section -->
        <section id="workouts" class="tab-content active"> <!-- Renamed section -->
            
                <div class="section-header">
                    <h2>Workout Plans</h2>
                    <form style="display: inline;" class="search-bar" action="searchresults" method="get">
                        <input type="text" name="query" placeholder="Search..." aria-label="Search">
                    </form>
                </div>
                
            </h2> <!-- Section title -->

            <div class="product-grid">
                <!-- Cardio Routine -->
                <div class="product-card">
                    <img src="images/cardio.jpg" alt="Cardio Routine">
                    <div class="overlay">
                        <h2>Cardio Routine</h2>
                        <p>10-30 min routines to boost endurance safely.</p>
                        <p class="price">£19.99</p>
                        <a href="productdetails?product=cardio" target="_blank"><button>View Details</button></a>
                    </div>
                </div>
                <!-- Strength Routine -->
                <div class="product-card">
                    <img src="images/strength.jpg" alt="Strength Routine">
                    <div class="overlay">
                        <h2>Strength Routine</h2>
                        <p>Build muscle and strength without injury.</p>
                        <p class="price">£24.99</p>
                        <a href="productdetails?product=strength" target="_blank"><button>View Details</button></a>
                    </div>
                </div>
                <!-- Flexibility Routine -->
                <div class="product-card">
                    <img src="images/flexibility.jpg" alt="Flexibility Routine">
                    <div class="overlay">
                        <h2>Flexibility Routine</h2>
                        <p>Improve flexibility and mobility with safe techniques.</p>
                        <p class="price">£14.99</p>
                        <a href="productdetails?product=flexibility" target="_blank"><button>View Details</button></a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Supplements Section -->
        <section id="supplements" class="tab-content">
            <h2>Supplements</h2> <!-- Section title -->
            <div class="product-grid">
                <!-- Creatine -->
                <div class="product-card">
                    <img src="images/creatine.jpg" alt="Creatine Supplement">
                    <div class="overlay">
                        <h2>Creatine</h2>
                        <p>Enhances strength and power. Ideal for muscle growth.</p>
                        <p class="price">£29.99</p>
                        <a href="productdetails?product=creatine" target="_blank"><button>View Details</button></a>
                    </div>
                </div>
                <!-- Whey Protein -->
                <div class="product-card">
                    <img src="images/wheyprotein.jpg" alt="Whey Protein">
                    <div class="overlay">
                        <h2>Whey Protein</h2>
                        <p>Rapid absorption for post-workout recovery.</p>
                        <p class="price">£34.99</p>
                        <a href="productdetails?product=whey" target="_blank"><button>View Details</button></a>
                    </div>
                </div>
                <!-- Plant-based Protein -->
                <div class="product-card">
                    <img src="images/plantbasedprotein.jpg" alt="Plant-based Protein">
                    <div class="overlay">
                        <h2>Plant-Based Protein</h2>
                        <p>Vegan-friendly protein for muscle growth and repair.</p>
                        <p class="price">£26.99</p>
                        <a href="productdetails?product=plant-based" target="_blank"><button>View Details</button></a>
                    </div>
                </div>
            </div>
        </section>
    </main>

<!-- JavaScript Functionality -->
    <script>
        function openTab(tabName) {
            // Get all tab contents and hide them
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => content.classList.remove('active'));
             // Activate the selected tab
            document.getElementById(tabName).classList.add('active');
        }

    // Developer: Georgi Tsarev, Student ID: 230132296, Function: Allows customers to filter products based on name and description using RegEx

    function filterProducts(inputSelector, productSelector) {
    const input = document.querySelector(inputSelector);

    if (!input) {
      console.error(`Input element not found for selector: ${inputSelector}`);
      return;
    }
    input.addEventListener('input', function () {
      const q = this.value.trim();

      // Filter special characters for regex
      const escapeQ = query.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, '\\$&');
      const regex = new RegExp(escapeQ, 'i');  
      const products = document.querySelectorAll(`${productSelector} .product-card`);

      products.forEach(product => {
        const title = product.querySelector('.overlay h2').textContent;
        const description = product.querySelector('.overlay p').textContent;

        if (regex.test(title) || regex.test(description)) {
          product.style.display = 'block';
        } else {
          product.style.display = 'none';
        }
      });
    });
  }
  // Allows the search bar to work dynamically
  document.addEventListener('DOMContentLoaded', () => { 
  });


    </script>

<footer>
    <div class="contact-details">
      <p>Aston St, Birmingham B4 7ET</p>
      <p>Phone: <a href="tel:0121 204 3000">0121 204 3000</a></p>
      <p>Email: <a href="mailto:info@example.com">Aston35@Fitness.com</a></p>
      <p>© 2024 Aston35Fitness. All rights reserved.</p>
      <a href="PrivacyPolicy">Privacy Policy</a> | <a href="Term Of Service">Terms of Service</a>
      </div>
  </footer>

  </section>
</body>
</html>
