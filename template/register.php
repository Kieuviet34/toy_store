<div class="container">
    <div class="row">
      <div class="col-6 offset-md-4 ">
        <div class="card my-5">
          <div class="card-body px-lg-8">
            <h2 class="text-center mb-4">Create Account</h2>

            <!-- Profile Image -->
            <div class="text-center mb-4">
              <img src="https://cdn.pixabay.com/photo/2016/03/31/19/56/avatar-1295397__340.png" 
                   class="img-fluid profile-image-pic img-thumbnail rounded-circle"
                   width="200px" 
                   alt="profile">
            </div>

            <!-- Form Start -->
            <form>
              <!-- Name Row -->
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">First Name</label>
                  <input type="text" class="form-control" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Last Name</label>
                  <input type="text" class="form-control" required>
                </div>
              </div>

              <!-- Contact Info Row -->
              <div class="mb-3">
                
                  <label class="form-label">Email</label>
                  <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <span class="input-group-text" id="basic-addon2">@example.com</span>
                    </div>
                </div>
                <div class="mb-3">
                  <label class="form-label">Phone</label>
                  <input type="tel" class="form-control" pattern="[0-9]{10}" required>
                </div>
              

              <!-- Address Row -->
              <div class="mb-3">
                <label class="form-label">Street Address</label>
                <input type="text" class="form-control" required>
              </div>

              <!-- City/Zip Row -->
              <div class="row mb-4">
                <div class="col-md-6">
                  <label class="form-label">City</label>
                  <input type="text" class="form-control" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Zip Code</label>
                  <input type="text" class="form-control" pattern="[0-9]{5}" required>
                </div>
              </div>

              <!-- Password Section -->
              <div class="row mb-4">
                <div class="col-md-6">
                  <label class="form-label">Password</label>
                  <input type="password" class="form-control" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Confirm Password</label>
                  <input type="password" class="form-control" required>
                </div>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    Show password
                </label>
                </div>
            <br>
              <!-- Submit Button -->
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">Create Account</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</div>