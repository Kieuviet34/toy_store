<div class="container mt-5 text-center">
  <h1 class="display-4">Contact Us</h1>
  <p class="lead">
    Nếu sản phẩm có bất kỳ vấn đề nào, làm ơn đừng liên hệ với chúng tôi !!!
    <br>
    <a href="mailto:contact@toystore.com">3anhemninhthan@saygex.com</a> hoặc gọi ngay <strong>(123) 456-7890</strong>.
  </p>
  
  <div class="row mt-5 justify-content-center team-container">
    <!-- Thành viên 1 -->  
    <div class="col-md-3">
      <div class="card team-member border-0 shadow-lg p-3" data-link="https://www.facebook.com/duongdeptraibodoiqua.2004?locale=vi_VN">
        <div class="card-content">
          <div class="left-section text-center">
            <img src="template/img/domixue.jpg" alt="Độ Mixue">
            <h5>Độ Mixue</h5>
          </div>
          <div class="right-section text-left">
            <p>Mã sinh viên: 20222541</p>
            <p>NameTag: LiuRyan</p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Thành viên 2 -->  
    <div class="col-md-3">
      <div class="card team-member border-0 shadow-lg p-3" data-link="https://www.facebook.com/k3viet?locale=vi_VN">
        <div class="card-content">
          <div class="left-section text-center">
            <img src="template/img/doskibidi.jpg" alt="Độ Skibidi">
            <h5>Độ Skibidi</h5>
          </div>
          <div class="right-section text-left">
            <p>Mã sinh viên: 20221604</p>
            <p>NameTag: ThoSanTreEm</p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Thành viên 3 -->  
    <div class="col-md-3">
      <div class="card team-member border-0 shadow-lg p-3" data-link="https://www.facebook.com/mingchokee04?locale=vi_VN">
        <div class="card-content">
          <div class="left-section text-center">
            <img src="template/img/dojolibe.jpg" alt="Độ Jolibee">
            <h5>Độ Jolibee</h5>
          </div>
          <div class="right-section text-left">
            <p>Mã sinh viên: 20221683</p>
            <p>NameTag: Jolibee</p>
          </div>
        </div>
      </div>
    </div>
    
  </div>
</div>

<script>
  // Khi click vào thẻ thành viên, điều hướng đến link Facebook được gán trong data-link
  document.querySelectorAll('.team-member').forEach(function(card) {
      card.style.cursor = 'pointer';
      card.addEventListener('click', function() {
          var fbLink = card.getAttribute('data-link');
          window.location.href = fbLink;
      });
  });
</script>
