$(document).ready(function () {

    // Dynamically add the modal HTML structure
    function createModal() {
        const modalHTML = `
            <div class="modal-overlay" id="upgradeModal">
                <div class="modal-box">
                    <!-- Left Section: Illustration -->
                    <div class="modal-illustration">
                        <img src="../img/aboutus.jpg" alt="Illustration" />
                    </div>

                    <!-- Right Section: Content -->
                    <div class="modal-content">
                        <h1>Become a Premium Member</h1>
                        <p>Unlock all these amazing features and enhance your experience:</p>

                        <!-- Features List -->
                        <div class="feature">
                            <span class="feature-icon"></span>
                            <span class="feature-text">Request your own appointments</span>
                        </div>
                        <div class="feature">
                            <span class="feature-icon"></span>
                            <span class="feature-text">Cancel any appointment at your own convenience</span>
                        </div>
                        <div class="feature">
                            <span class="feature-icon"></span>
                            <span class="feature-text">Edit your profile details at your own will</span>
                        </div>
                        <div class="feature">
                            <span class="feature-icon"></span>
                            <span class="feature-text">Rate you experiences</span>
                        </div>

                        <!-- Upgrade Button -->
                        <a href="subscribe.php" class="btn-upgrade">Upgrade Now — For only $29.99</a>
                       <button class="btn-close" id="notNowBtn">Not Now</button>
                    </div>
                </div>
            </div>
        `;

        // Append the modal to the body
        $('body').append(modalHTML);
    }

    // Add custom styles for the modal
    function addModalStyles() {
        const modalStyles = `
            <style>
                body {
                    margin: 0;
                    font-family: 'Poppins', sans-serif;
                    background-color: #f4f6f9;
                    color: #333;
                }

                /* Overlay Background */
                .modal-overlay{
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.8);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 1000;
                    visibility: hidden;
                    opacity: 0;
                    transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
                }

                .modal-overlay.active {
                    visibility: visible;
                    opacity: 1;
                }

                /* Modal Box */
                #upgradeModal .modal-box {
                    background-color: #1b1e29;
                    width: 1000px;
                    border-radius: 15px;
                    display: flex;
                    overflow: hidden;
                    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
                    color: #fff;
                }

                /* Left Side - Illustration */
                #upgradeModal .modal-illustration {
                    background: linear-gradient(135deg, #4c84ff, #5de0e6);
                    flex: 1;
                    padding: 20px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }

                /* Right Side - Content */
                #upgradeModal .modal-content {
                    flex: 1;
                    padding: 40px;
                }

                #upgradeModal .modal-content h1 {
                    font-size: 24px;
                    margin-bottom: 15px;
                    text-transform: uppercase;
                    font-weight: 600;
                    letter-spacing: 1px;
                    color: #aaa;
                }

                #upgradeModal .modal-content p {
                    margin-bottom: 20px;
                    font-size: 14px;
                    color: #ddd;
                }

                #upgradeModal .feature {
                    display: flex;
                    align-items: center;
                    margin-bottom: 15px;
                }

                #upgradeModal .feature-icon {
                    font-size: 20px;
                    margin-right: 15px;
                    color: #5de0e6;
                }

                .feature-text {
                    font-size: 14px;
                }

                /* Upgrade Button */
                #upgradeModal .btn-upgrade {
                    display: inline-block;
                    padding: 12px 20px;
                    background: #5de0e6;
                    color: #1b1e29;
                    font-weight: 600;
                    border-radius: 25px;
                    text-align: center;
                    text-decoration: none;
                    margin-top: 10px;
                    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
                    transition: all 0.3s ease;
                }

                #upgradeModal .btn-upgrade:hover {
                    background: #4c84ff;
                    color: #fff;
                    transform: translateY(-3px);
                }
                #upgradeModal .btn-close {
                    background: none;
                    border: none;
                    color: #aaa;
                    font-size: 20px;
                    width:6em;
                    cursor: pointer;
                    transition: color 0.3s ease;
                    align-self:center;
                }

                #upgradeModal .btn-close:hover {
                    color: #fff;
                }
            </style>
        `;

        // Append styles to the head
        $('head').append(modalStyles);
    }
$(".upgrade-link").on("click",function(){
    showModal();
    console.log("click")
})
    // Show the modal with animation
    function showModal() {
        setTimeout(function () {
            $("#upgradeModal").addClass("active");
        console.log("open")

        }, 500); // Delay for smooth entry
    }
    function closeModal() {
        $("#notNowBtn").on("click", function () {
            $("#upgradeModal").removeClass("active");
        });
    }
    // Initialize Modal
    createModal();
    addModalStyles();
    showModal();
    closeModal();
});
