<h1>Create an account</h1>
<form action="/register" method="POST" >
    <div class="row">
        <div class="col">
            <div class="mb-3">
                <label class="form-label">Firstname</label>
                <input type="text" class="form-control" name="firstname">
            </div>
        </div>
        <div class="col">
            <div class="mb-3">
                <label>Lastname</label>
                <input type="text" class="form-control" name="lastname">
            </div>
        </div>
    </div>


    <div class="mb-3">
        <label>Email</label>
        <input type="text" class="form-control" name="emain">
    </div>
    <div class="mb-3">
        <label>Password</label>
        <input type = 'password' class="form-control" name = "password">
    </div>
    <div class="mb-3">
        <label>Confirm Password</label>
        <input type = 'password' class="form-control" name = "confirmPassword">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>