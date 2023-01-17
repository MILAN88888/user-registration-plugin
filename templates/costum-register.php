<!--Registration form-->
<section class="main-content  mb-2">
    <div class="container" style="margin-left: 25vw;">
        <div id="content" class="" role="main">
            <div class="row py-5 justify-content-between d-flex align-item-center">
                <!-- Registeration Form -->
                <div class="col-md-6 col-lg-6 ml-auto formRegister">
                    <h2 class="mb-2">Registration Form</h2>
                    <form id="register-form" action="" method="post">
                        <!-- Nonce for CSRF -->
                        <?php wp_nonce_field('register_nonce_action', 'register_nonce_field'); ?>

                        <!-- First Name -->
                        <div class="col-lg-12 mb-4">
                            <label for="firstName" class="form-label">First Name&nbsp;<span class="required">*</span></label>
                            <input id="firstName" type="text" name="firstname" placeholder="First Name" class="form-control inputGrey border-left-0 border-md" required>
                        </div>

                        <!-- Last Name -->
                        <div class="col-lg-12 mb-4">
                            <label for="lastName" class="form-label">Last Name&nbsp;<span class="required">*</span></label>
                            <input id="lastName" type="text" name="lastname" placeholder="Last Name" class="form-control inputGrey border-left-0 border-md" required>
                        </div>

                        <!-- Email Address -->
                        <div class="col-lg-12 mb-4">
                            <label for="email" class="form-label">Email Address&nbsp;<span class="required">*</span></label>
                            <input id="email" type="email" name="email" placeholder="Email Address" class="form-control inputGrey border-left-0 border-md" required>
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label">Password&nbsp;<span class="required">*</span></label>
                            <input id="password" type="password" name="password" placeholder="Enter Your Password" class="form-control inputGrey border-left-0 border-md" required>
                        </div>

                        <!-- reviews-->
                        <div class="mb-4">
                            <label for="review" class="form-label">Review For Product&nbsp;<span class="required">*</span></label>
                            <textarea id="review" class="form-control inputGrey border-left-0 border-md" name="review" placeholder="Please Enter Your Reviews about our Product" required></textarea>
                        </div>

                        <!--Review Rating -->
                        <div class="mb-4">
                            <label for="review-rating" class="form-label">Rating&nbsp;<span class="required">*</span></label>
                            <input id="review-rating" name="review_rating" class="form-control inputGrey border-left-0 border-md" type="number" min=0 max=5 step="1" name="review-rating" required />
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group col-lg-12 mx-auto mb-0 mt-5 text-center">
                            <button type="submit" name="register" class="btn btn-primary btn-lg" style="width: 220px;">
                                <span class="font-weight-bold">Register</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    </div>
</section>