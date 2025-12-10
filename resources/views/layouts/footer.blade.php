<style>
/*----------- FOOTER -----------*/

        .footer{
            background-color: var(--primary);
            color: white;
            padding: 40px 20px;
        }

        .footer-content{
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .footer-section h3{
            font-size: 18px;
            margin-bottom: 15px;
            color: white;
        }

        .footer-links{
            list-style: none;
            margin-bottom: 10px;
        }

        .footer-links a{
            color: white;
            text-decoration: none;
        }

        .footer-links a:hover{
            color: #c6c6c6;
        }

        .social-list{
            flex-direction: column;
            gap: 15px;
            margin-top: 10px;
        }

        .social-link{
            display: flex;
            gap: 8px;
            color: white;
            text-decoration: none;
        }

        .social-link:hover{
            color: #c6c6c6;
        }

        .footer-bottom{
            margin-top: 30px;
            padding-top: 20px;
            border-top: 0.1px solid var(--text-light);
            text-align: center;
            font-size: 14px;
            color: white;
        }

        .footer-bottom a{
            text-decoration: none;
            color: var(--text-dark);
        }

        .footer-bottom a:hover{
            text-decoration: underline;
            color: var(--text-light);
        }

        /*----------- MAP -----------*/
        #map { width:100%; height:520px; border-radius:8px; overflow:hidden; margin-bottom:40px; }

        /*---------------------- RESPONSIVE DESIGN ----------------------*/

        @media (min-width: 1024px){
            .container{
                width: 100%;
                min-width: 1024px;
                margin: 0 auto;
                padding: 0 20px;
            }

            body{
                font-size: 1rem;
            }
            .user-actions{
                display: flex;
                align-items: center;
                justify-self: space-between;
                gap: 50px;
                font-size: medium;
            }
            .footer-content{
                display: grid;
                font-size: smaller;
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 30px;
            }
        }
</style>
<!-- Footer (preserved design) -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Hitchhike</h3>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Product</h3>
                    <ul class="footer-links">
                        <li><a href="#">Book a Ride</a></li>
                        <li><a href="#">Offer a Ride</a></li>
                        <li><a href="#">How it works</a></li>
                        <li><a href="#">Safety</a></li>
                        <li><a href="#">Pricing</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Support</h3>
                    <ul class="footer-links">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Community Guidelines</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Stay Updated</h3>
                    <p>Follow us on social media</p>
                    <div class="social-list">
                        <a href="#" class="social-link"><i class="fa-brands fa-facebook-f"></i>Facebook</a>
                        <a href="#" class="social-link"><i class="fa-brands fa-instagram"></i>Instagram</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Hitchhike.  | <a href="#"> Privacy Policy </a> | <a href="#"> Terms and Conditions </a></p>
            </div>
        </div>
    </footer>