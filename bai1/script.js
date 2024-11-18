function showLoginForm(event) {
  event.preventDefault();

  // Giả lập gửi dữ liệu đăng ký thành công
  alert("Đăng ký thành công! Bạn có thể đăng nhập.");

  // Ẩn form đăng ký và hiển thị form đăng nhập
  document.getElementById("register-form").classList.add("hidden");
  document.getElementById("login-form").classList.remove("hidden");
}
