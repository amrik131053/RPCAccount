<?php 
session_start();
include "css.php";
 if(isset($_SESSION['user_ac']))
{
header('Location:Dashboard.php');
}
else
{
?>
<div class="page page-center " style="background-color:#223260;">
    <div class="container container-tight py-4">
        <div class="card card-md">
            <div class="card-body mb-4 ">
                <div class="text-center">
                    <a href="." class="navbar-brand navbar-brand-autodark"><img src="styles/images/join-logo.png"
                            height="55" alt=""></a>
                </div>
                <h2 class="h2 text-center mt-5">Login to your account</h2>

                <form action="login.php" class="sign-in-form" method="post">
                    <div class="mb-2">
                        <input type="hidden" name="code" value="7">
                        <label class="form-label">IDNo</label>
                        <input type="number" name="user" class="form-control input_user"
                            value="<?php if(isset($_COOKIE["user_login"])) { echo $_COOKIE["user_login"]; } ?>"
                            placeholder="Employee ID" autocomplete="username" required>
                    </div>

                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label mb-0">Password</label>
                        </div>
                        <div class="input-group">
                            <input type="password"
                                value="<?php if(isset($_COOKIE["userpassword"])) { echo $_COOKIE["userpassword"]; } ?>"
                                name="pass" id="password" class="form-control input_pass" placeholder="Your Password"
                                autocomplete="new-password" required>

                            <span class="input-group-text" onclick="togglePassword()" style="cursor:pointer;">
                                <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 5c-7 0-10 7-10 7s3 7 10 7s10-7 10-7s-3-7-10-7z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>

                                <svg id="eyeClose" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                                    <path
                                        d="M17.94 17.94A10.94 10.94 0 0 1 12 19c-7 0-10-7-10-7a21.77 21.77 0 0 1 5.06-5.94">
                                    </path>
                                    <path d="M1 1l22 22"></path>
                                    <path
                                        d="M9.9 4.24A10.94 10.94 0 0 1 12 5c7 0 10 7 10 7a21.77 21.77 0 0 1-4.22 5.18">
                                    </path>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check mb-0">
                                <input type="checkbox" name="remember" id="remember" class="form-check-input"
                                    <?php if(isset($_COOKIE["user_login"])) { ?> checked <?php } ?>>
                                <label class="form-check-label" for="remember" style="color:#223260;">
                                    Remember me
                                </label>
                            </div>

                        </div>
                    </div>

                    <center> <?php
                                                if(isset($_SESSION['incorrect']))
                                                {
                                                    echo $_SESSION['incorrect'];
                                                }
                                                unset($_SESSION['incorrect']);
                                                if(isset($_SESSION['not_valid']))
                                                {
                                                    echo $_SESSION['not_valid'];
                                                }   
                                                unset($_SESSION['not_valid']);
                                            ?></center>

                    <div class="form-footer">
                        <button type="submit" class="btn w-100" style="background-color: #223260;color:white;">Sign
                            in</button>
                    </div>
                </form>

            </div>
            <div class="hr-text">or</div>
            <div class="card-body">
                <div class="row">

                    <div class="col">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-brand-facebook">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3" />
                        </svg>

                    </div>
                    <div class="col">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-brand-instagram">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 8a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z" />
                            <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                            <path d="M16.5 7.5v.01" />
                        </svg>

                    </div>
                    <div class="col">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-brand-pinterest">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M8 20l4 -9" />
                            <path
                                d="M10.7 14c.437 1.263 1.43 2 2.55 2c2.071 0 3.75 -1.554 3.75 -4a5 5 0 1 0 -9.7 1.7" />
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        </svg>

                    </div>
                    <div class="col">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-brand-youtube">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M2 8a4 4 0 0 1 4 -4h12a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-12a4 4 0 0 1 -4 -4v-8z" />
                            <path d="M10 9l5 3l-5 3z" />
                        </svg>

                    </div>
                    <div class="col">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-brand-twitter">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M22 4.01c-1 .49 -1.98 .689 -3 .99c-1.121 -1.265 -2.783 -1.335 -4.38 -.737s-2.643 2.06 -2.62 3.737v1c-3.245 .083 -6.135 -1.395 -8 -4c0 0 -4.182 7.433 4 11c-1.872 1.247 -3.739 2.088 -6 2c3.308 1.803 6.913 2.423 10.034 1.517c3.58 -1.04 6.522 -3.723 7.651 -7.742a13.84 13.84 0 0 0 .497 -3.753c0 -.249 1.51 -2.772 1.818 -4.013z" />
                        </svg>

                    </div>
                    <div class="col">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-brand-linkedin">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M8 11v5" />
                            <path d="M8 8v.01" />
                            <path d="M12 16v-5" />
                            <path d="M16 16v-3a2 2 0 1 0 -4 0" />
                            <path d="M3 7a4 4 0 0 1 4 -4h10a4 4 0 0 1 4 4v10a4 4 0 0 1 -4 4h-10a4 4 0 0 1 -4 -4z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-3" style="color:white;">
            Having an issue regarding login Whatsapp -91 78146-79220
        </div>
    </div>
</div>
<!-- Libs JS -->
<!-- Tabler Core -->
<script>
function togglePassword() {
    let password = document.getElementById('password');
    let eyeOpen = document.getElementById('eyeOpen');
    let eyeClose = document.getElementById('eyeClose');

    if (password.type === 'password') {
        password.type = 'text';
        eyeOpen.style.display = 'none';
        eyeClose.style.display = 'block';
    } else {
        password.type = 'password';
        eyeOpen.style.display = 'block';
        eyeClose.style.display = 'none';
    }
}
</script>
<?php
}
include "scripts.php";
?>