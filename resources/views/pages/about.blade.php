@extends('layouts.app')

@push('head')
    @vite(['resources/css/about.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<div class="container">
        <header>
            <h1>About Our Company</h1>
            <p class="subtitle">Learn more about our mission, values, and team. Get in touch with us for any inquiries or collaborations.</p>
        </header>
        
        <div class="nav-tabs">
            <button class="nav-tab active" data-target="about">About Us</button>
            <button class="nav-tab" data-target="contact">Contact Us</button>
        </div>
        
        <section id="about" class="content-section active">
            <div class="banner">
                <h2>HitchHike</h2>
            </div>
            
            <div class="company-description">
                <h2>Company Description</h2>
                <p>We are a technology company dedicated to creating innovative solutions that simplify complex problems. Founded in 2015, we've grown from a small startup to a team of 50+ professionals serving clients across 20 countries.</p>
                <p>Our platform connects people with cutting-edge tools that enhance productivity, foster collaboration, and drive business growth. We believe in the power of technology to transform industries and improve lives.</p>
            </div>
            
            <div class="mission-vision">
                <div class="mission">
                    <h3><i class="fas fa-bullseye"></i> Our Mission</h3>
                    <p>To empower businesses and individuals with intuitive technology solutions that streamline workflows, enhance connectivity, and unlock new possibilities for growth and innovation.</p>
                </div>
                
                <div class="vision">
                    <h3><i class="fas fa-eye"></i> Our Vision</h3>
                    <p>To create a world where technology seamlessly integrates with human potential, breaking down barriers and creating opportunities for all to thrive in the digital age.</p>
                </div>
            </div>
            
            <div class="values">
                <h2>Our Core Values</h2>
                <div class="values-grid">
                    <div class="value-card">
                        <i class="fas fa-users"></i>
                        <h3>Collaboration</h3>
                        <p>We believe in the power of teamwork and diverse perspectives to solve complex challenges.</p>
                    </div>
                    
                    <div class="value-card">
                        <i class="fas fa-lightbulb"></i>
                        <h3>Innovation</h3>
                        <p>We constantly push boundaries and explore new possibilities to deliver cutting-edge solutions.</p>
                    </div>
                    
                    <div class="value-card">
                        <i class="fas fa-shield-alt"></i>
                        <h3>Integrity</h3>
                        <p>We operate with transparency, honesty, and ethical practices in all our interactions.</p>
                    </div>
                    
                    <div class="value-card">
                        <i class="fas fa-heart"></i>
                        <h3>Customer Focus</h3>
                        <p>Our customers are at the heart of everything we do, and their success is our ultimate measure.</p>
                    </div>
                </div>
            </div>
            
            <div class="team-section">
                <h2>Meet Our Leadership Team</h2>
                <div class="team-grid">
                    <div class="team-member">
                        <img src="https://www.pngmart.com/files/12/Stuart-Minion-PNG-Clipart.png" alt="Johan Emmanuel Inigo" class="member-photo">
                        <h3>Johan Emmanuel Inigo</h3>
                        <p>Vice CEO</p>
                        <p>Visionary leader with 3 years in tech industry(Project sa School)</p>
                    </div>
                    
                    <div class="team-member">
                        <img src="https://w0.peakpx.com/wallpaper/381/798/HD-wallpaper-minions-funny.jpg" alt="Josef Zedril" class="member-photo">
                        <h3>Josef Zedril</h3>
                        <p>CEO</p>
                        <p>Expert in software architecture, Design, and innovation(Nag isip neto nakakamatay na project)</p>
                    </div>
                    
                    <div class="team-member">
                        <img src="https://gallery.yopriceville.com/var/albums/Free-Clipart-Pictures/Cartoons-PNG/Transparent_Minion_PNG_Image.png?m=1629784138" alt="Royce Lawrence" class="member-photo">
                        <h3>Royce Lawrence</h3>
                        <p>Head of Back-End Team</p>
                        <p>Passionate about user experience and back-end management(ay basta)</p>
                    </div>
                </div>
            </div>
            
            <div class="history">
                <h2>Our Journey</h2>
                <p>Founded in a small garage in 2015, our company started with a simple idea: technology should work for people, not the other way around. Our first product, a collaboration tool for remote teams, quickly gained traction.</p>
                <p>By 2018, we had expanded our product line and secured our first round of funding. Today, we serve over 10,000 businesses worldwide and continue to innovate with new solutions that address evolving market needs.</p>
                <p>Our commitment to excellence has earned us several industry awards, including "Most Innovative Tech Company" in 2022 and "Best Workplace" in 2023.</p>
            </div>
        </section>
        
        <!-- Contact Us Section -->
        <section id="contact" class="content-section">
            <h2>Get In Touch</h2>
            <p>Have questions or want to learn more about our services? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
            
            <div class="contact-container">
                <div class="contact-info">
                    <h3>Contact Information</h3>
                    
                    <div class="contact-details">
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <h4>Email</h4>
                                <p>hello@company.com</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <div>
                                <h4>Phone</h4>
                                <p>+1 (555) 123-4567</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <h4>Office Address</h4>
                                <p>123 Innovation Drive<br>Tech Valley, CA 94025<br>United States</p>
                            </div>
                        </div>
                    </div>
                    
                    <h3>Follow Us</h3>
                    <div class="social-links">
                        <a href="#" class="social-link" target="_blank">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link" target="_blank">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link" target="_blank">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link" target="_blank">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                    
                    <div class="map-placeholder">
                        <i class="fas fa-map-marked-alt"></i>
                        <span style="margin-left: 10px;">Interactive Map Would Appear Here</span>
                    </div>
                </div>
                
                <div class="contact-form-container">
                    <form class="contact-form" id="contactForm">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" class="form-control" placeholder="Enter your full name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" class="form-control" placeholder="Enter your email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" class="form-control" placeholder="What is this regarding?">
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" class="form-control" placeholder="How can we help you?" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-block">Send Message</button>
                    </form>
                </div>
            </div>
        </section>
        
        <footer>
            <p>&copy; 2025 Company Name. All rights reserved.</p>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab navigation
            const navTabs = document.querySelectorAll('.nav-tab');
            const contentSections = document.querySelectorAll('.content-section');
            
            navTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    
                    // Update active tab
                    navTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Show target section
                    contentSections.forEach(section => {
                        section.classList.remove('active');
                        if (section.id === targetId) {
                            section.classList.add('active');
                        }
                    });
                });
            });
            
            // Contact form submission
            const contactForm = document.getElementById('contactForm');
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Get form values
                    const name = document.getElementById('name').value;
                    const email = document.getElementById('email').value;
                    const message = document.getElementById('message').value;
                    
                    // In a real application, you would send this data to a server
                    // For this example, we'll just show an alert
                    alert(`Thank you, ${name}! Your message has been sent successfully. We'll get back to you at ${email} as soon as possible.`);
                    
                    // Reset form
                    contactForm.reset();
                });
            }
            
            // Form validation
            const formInputs = document.querySelectorAll('.form-control');
            formInputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value.trim() === '' && this.hasAttribute('required')) {
                        this.style.borderColor = 'red';
                    } else {
                        this.style.borderColor = '#ddd';
                    }
                });
            });
        });
    </script>
@endsection