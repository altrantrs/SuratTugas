<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">

  <title>Login</title>
</head>

<div class="container">
  <main>
    <section class="allocation">
      <div class="form-container">
        <form name="formlogin" action="get_login.php" method="POST">
          <div class="imgcontainer">
            <h2>Surat Tugas BPS Kabupaten Wonogiri</h2>
          </div>

          <div class="formcontainer">
            <label for="user"><b>Username</b></label>
            <input type="text" placeholder="Masukkan Username" name="user" required>

            <label for="pass"><b>Password</b></label>
            <input type="password" placeholder="Masukkan Password" name="pass" required>
            <div class="buttons">
              <button type="submit" id="save-btn" class="btn btn-save" name="submit">Login</button>
            </div>
          </div>
        </form>
      </div>
    </section>
  </main>
</div>
</body>

</html>