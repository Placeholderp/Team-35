<!-- Customer Testimonials Section -->
<section id="testimonials" class="py-5 my-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h3 class="category-header">What Our Customers Say</h3>
                <p class="mb-5">Join thousands of satisfied customers who have transformed their fitness journey with our products</p>
            </div>
        </div>
        
        <div class="testimonial-slider">
            <div class="row">
                <!-- Testimonial 1 -->
                <div class="col-md-4 mb-4">
                    <div class="testimonial-card">
                        <div class="testimonial-rating mb-3">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">"I've tried many protein supplements, but Aston35's quality is unmatched. Mixes perfectly and the taste is amazing!"</p>
                        <div class="testimonial-author d-flex align-items-center mt-4">
                            <div class="avatar mr-3">
                                <img src="{{ asset('/Images/avatar1.jpg') }}" alt="Customer" class="rounded-circle" width="50" height="50">
                            </div>
                            <div>
                                <h6 class="mb-0">Alex M.</h6>
                                <small class="text-muted">Verified Customer</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="col-md-4 mb-4">
                    <div class="testimonial-card">
                        <div class="testimonial-rating mb-3">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-text">"The pre-workout gives me exactly the energy I need without the jitters. Noticed significant improvements in my training!"</p>
                        <div class="testimonial-author d-flex align-items-center mt-4">
                            <div class="avatar mr-3">
                                <img src="{{ asset('/Images/avatar2.jpg') }}" alt="Customer" class="rounded-circle" width="50" height="50">
                            </div>
                            <div>
                                <h6 class="mb-0">Sarah T.</h6>
                                <small class="text-muted">Verified Customer</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="col-md-4 mb-4">
                    <div class="testimonial-card">
                        <div class="testimonial-rating mb-3">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half"></i>
                        </div>
                        <p class="testimonial-text">"The workout plans are detailed and easy to follow. I've seen more progress in 8 weeks than in the past year at the gym!"</p>
                        <div class="testimonial-author d-flex align-items-center mt-4">
                            <div class="avatar mr-3">
                                <img src="{{ asset('/Images/avatar3.jpg') }}" alt="Customer" class="rounded-circle" width="50" height="50">
                            </div>
                            <div>
                                <h6 class="mb-0">Michael D.</h6>
                                <small class="text-muted">Verified Customer</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Testimonial Section Styling */
    #testimonials {
        background-color: #f8f9fa;
        padding: 80px 0;
    }
    
    .testimonial-card {
        background-color: white;
        border-radius: 10px;
        padding: 30px;
        height: 100%;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .testimonial-rating i {
        color: #FFD700;
        font-size: 18px;
        margin-right: 2px;
    }
    
    .testimonial-text {
        color: #555;
        font-size: 16px;
        line-height: 1.6;
        font-style: italic;
        min-height: 100px;
        display: flex;
        align-items: center;
    }
    
    .testimonial-author h6 {
        color: #333;
        font-weight: 600;
    }
    
    .testimonial-author small {
        font-size: 12px;
    }
</style>