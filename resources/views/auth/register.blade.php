<x-layout>



     <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background" style="background-image: url(assets/img/education/showcase-1.webp);">
      <div class="container position-relative">
        <h1>Login Page</h1>
        <p>Esse dolorum voluptatum ullam est sint nemo et est ipsa porro placeat quibusdam quia assumenda numquam molestias.</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li class="current">Login Page</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Starter Section Section -->
    <section id="starter-section" class="starter-section section">

         <div class="container mt-2 mb-5">
        <div class="row">
            <div class="col-md-6 mx-auto">

                <div class="p-4 my-3 shadow-sm loginformContainer signupcontainer d-flex">



                      <div class="col-12 mx-auto">
                          <form action="{{route('postregister')}}" class="signupbox" method="POST" enctype="multipart/form-data">
                            @csrf
                            <h3 class="text-center fontcolor">Register Form</h3>


                              <div class="form-group mb-3">
                                <label for="username">Username</label>
                                <input
                                type="text"
                                class="form-control inputbox"
                                value="{{old('username')}}"
                                name="username" id="username" aria-describedby="emailHelp" value="{{ old('username') }}" placeholder="Enter Username"
                                required>


                                <x-error name="username"></x-error>
                              </div>

                              <div class="form-group mb-3">
                                <p class="text-danger showtext" id="threecharacter">Username must be 3 character at least</p>
                              </div>

                            <div class="form-group mb-3">
                              <label for="exampleInputEmail1">Email address</label>
                              <input
                              type="email"
                              class="form-control inputbox"
                                name="email"
                                value="{{old('email')}}"
                                id="exampleInputEmail1"

                                aria-describedby="emailHelp"
                                placeholder="Enter email"
                                required>

                                <x-error name="email"></x-error>
                            </div>

                             <div class="form-group mb-3 permissions my-2">
                                <label for="role_id">Select Role</label>
                                <select name="role_id" id="role_id" class="form-control" required>
                                    <option value="">-- Choose a role --</option>
                                    @forelse ($systemroles as $systemrole)
                                        <option value="{{ $systemrole->id }}">{{ $systemrole->role }}</option>
                                    @empty
                                        <option disabled>No roles available</option>
                                    @endforelse
                                </select>
                                <x-error name="role_id"></x-error>
                            </div>




                            <div class="form-group mb-3">
                              <label for="exampleInputPassword1">Password</label>
                              <div class="position-relative">
                                  <input
                                  type="password" class="form-control inputbox"
                                  name="password"
                                  required
                                  id="password" placeholder="Password">
                                  <button type="button" id="togglePassword" class="btn btn-outline-secondary position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); border: none; background: none; padding: 0; width: 30px; height: 30px;">
                                      <i class="fas fa-eye" id="eyeIcon"></i>
                                  </button>
                              </div>
                              <x-error name="password"></x-error>
                            </div>

                            <div class="form-group mb-3">
                              <p class="text-danger showtext" id="passwordeightcharacter">Passowrd must be 8 character at least</p>
                              <p class="text-danger showtext" id="passworduppercase">Passowrd must contain uppercase</p>
                              <p class="text-danger showtext" id="passwordlowercase">Passowrd must contain lowercase</p>
                              <p class="text-danger showtext" id="passwordnumber">Passowrd must contain number</p>
                              <p class="text-danger showtext" id="passwordspecialcharacter">Passowrd must contain Special Character From(@$!%*?&)</p>
                            </div>

                            <div class="form-group mb-3">
                              <label for="password_confirmation">Confirm Password</label>
                              <div class="position-relative">
                                  <input type="password" class="form-control inputbox" name="password_confirmation" placeholder="Confirm Password" id="password_confirmation" required>
                                  <button type="button" id="togglePasswordConfirmation" class="btn btn-outline-secondary position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); border: none; background: none; padding: 0; width: 30px; height: 30px;">
                                      <i class="fas fa-eye" id="eyeIconConfirmation"></i>
                                  </button>
                              </div>
                              <x-error name="password_confirmation"></x-error>
                              @error('password_confirmation')
                                  <p>{{$message}}</p>
                              @enderror
                            </div>

                            <div class="form-group mb-3">
                              <label for="userphoto">Photo</label>
                              <input
                              type="file" class="form-control inputbox"
                              name="userphoto" id="userphoto" placeholder="User Photo">

                              <x-error name="userphoto"></x-error>
                            </div>


                            <div class="d-grid mt-5 mx-auto">
                              <p>Already have a account? <a href="/login" class="bgfontcolor">Login</a> </p>
                              <button type="submit" id="submitbtn" name="submitLogin" class="btn rounded-5 btn-success">Sign up</button>

                            </div>
                          </form>
                      </div>



                </div>



            </div>
        </div>
    </div>

    </section><!-- /Starter Section Section -->

  </main>

</x-layout>

<script>
    function validateInput(inputValue) {

        var hasMinimumLength = inputValue.length >= 3;


        if (hasMinimumLength) {
            $("#threecharacter").removeClass('text-danger d-block');
            $('#threecharacter').addClass('text-success d-none');
        } else {
            $('#threecharacter').removeClass('text-success d-none');
            $("#threecharacter").addClass('text-danger d-block');

        }
    }

    $("#username").on('keyup', function() {
        $value = $("#username").val();

        validateInput($value);
    });

    function validatePassword(password) {
        let containsLowercase = /[a-z]/.test(password);
        let containsUppercase = /[A-Z]/.test(password);
        let containsSpecialChar = /[@$!%*?&]/.test(password);
        let containsNumber = /\d/.test(password);
        let hasMinimumLength = password.length >= 8;

        if (containsLowercase) {
            $("#passwordlowercase").removeClass("text-danger");
            $("#passwordlowercase").addClass("text-success");
        } else {
            $("#passwordlowercase").removeClass("text-success");
            $("#passwordlowercase").addClass("text-danger");
        }

        if (containsUppercase) {
            $("#passworduppercase").removeClass("text-danger");
            $("#passworduppercase").addClass("text-success");
        } else {
            $("#passworduppercase").removeClass("text-success");
            $("#passworduppercase").addClass("text-danger");
        }

        if (containsSpecialChar) {
            $("#passwordspecialcharacter").removeClass("text-danger");
            $("#passwordspecialcharacter").addClass("text-success");
        } else {
            $("#passwordspecialcharacter").removeClass("text-success");
            $("#passwordspecialcharacter").addClass("text-danger");
        }

        if (containsNumber) {
            $("#passwordnumber").removeClass("text-danger");
            $("#passwordnumber").addClass("text-success");
        } else {
            $("#passwordnumber").removeClass("text-success");
            $("#passwordnumber").addClass("text-danger");
        }

        if (hasMinimumLength) {
            $("#passwordeightcharacter").removeClass("text-danger");
            $("#passwordeightcharacter").addClass("text-success");
        } else {
            $("#passwordeightcharacter").removeClass("text-success");
            $("#passwordeightcharacter").addClass("text-danger");
        }
    }

    $("#password").on('keyup', function() {
        $value = $("#password").val();

        validatePassword($value);
    });

    // Password visibility toggle functionality
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    const eyeIconConfirmation = document.getElementById('eyeIconConfirmation');

    // Password visibility toggle
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Toggle eye icon
        if (type === 'password') {
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        } else {
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        }
    });

    // Password confirmation visibility toggle
    togglePasswordConfirmation.addEventListener('click', function() {
        const type = passwordConfirmationInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirmationInput.setAttribute('type', type);
        
        // Toggle eye icon
        if (type === 'password') {
            eyeIconConfirmation.classList.remove('fa-eye-slash');
            eyeIconConfirmation.classList.add('fa-eye');
        } else {
            eyeIconConfirmation.classList.remove('fa-eye');
            eyeIconConfirmation.classList.add('fa-eye-slash');
        }
    });
</script>
