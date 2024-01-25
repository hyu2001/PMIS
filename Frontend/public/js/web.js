$(function(){
	$(document).on('click', '#subscribe', function(e) {
        swal({
          title: 'Submit email to subscribe',
          input: 'email',
          inputPlaceholder: 'Example@email.xxx',
          showCancelButton: true,
          confirmButtonText: 'Submit',
          showLoaderOnConfirm: true,
          preConfirm: (email) => {
            return new Promise((resolve) => {
              setTimeout(() => {
                if (email === 'example@email.com') {
                  swal.showValidationError(
                    'This email is already taken.'
                  )
                }
                resolve()
              }, 2000)
            })
          },
          allowOutsideClick: false
        }).then((result) => {
          if (result.value) {
            swal({
              type: 'success',
              title: 'Thank you for subscribe!',
              html: 'Submitted email: ' + result.value
            })
          }
        })
    });

    
// 可以把datarange-picker清空
$(function() {
  $('input[name="datefilter"]').daterangepicker({
      autoUpdateInput: false,
      locale: {
        cancelLabel: 'Clear',
        format: 'YYYY/MM/DD'
      }
  });
  $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
    $daterange = picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD');
    console.log("apply");
    filter_name = 'date';
    is_optgroup = false;
    filter_value = $daterange;
    filter_selected = true;
    setSession(filter_name, is_optgroup, filter_value, filter_selected);
  });

  $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
  });

  $('.applyBtn btn btn-sm btn-primary').click(function(){
    console.log("apply");
  })

  $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
    console.log("Cancel date");
    $(this).val('');
    filter_name = 'date';
    is_optgroup = false;
    filter_value = "null";
    filter_selected = true;
    setSession(filter_name, is_optgroup, filter_value, filter_selected);
  });
});

// $('.ms-select-all').onclick(function(){
//    console.log("Hi");
// });

$('.accordion h3').click(function(){
  $('body').toggleClass('close-bottom');
});

});

//Change Sort
var sortBy = document.getElementsByClassName('dropdown-item');
var sortName = ["letter", "date"];  //v1.0 還沒加成員排序
// var sortName = ["letter", "member", "date"];  //有加成員排序再用這個

//為每個sortBy標籤添加click事件
for (let i = 0; i < sortBy.length; i++) {
  sortBy[i].addEventListener('click', ()=>{
    console.log(sortBy[i].text);
    
    $.ajax({
      url: 'show_board/notfound.php',
      dataType: 'json',
      success: res =>{
          console.log(res)
          data = res;
      },
      error: err =>{
          console.log(err)
      },
  }).then(function(){
      if (data == 0) {
          document.getElementById('no-result').innerText = 'No result found';
      }
      else {
        $.ajax({
          type: "POST",
          url: "show_board/gantt_getdata.php", 
          data: { 
              change_sort: sortName[i]
          },
          success: function(response) {
            // 在此處處理PHP返回的資料
            taskData = response;
          },
          error: function(xhr, status, error) {
            console.error(error);
          }
        }).then(function(){
            gantt.parse(taskData);
        });
      }
    });
  });
}


// Scale zooming
const buttonSlide = (() => {
  const activeState = (item, btnItem) => {
    const btnActive = item.getElementsByClassName('js-btn-slide-active')[0];
    const itemBounding = item.getBoundingClientRect();
    const btnBounding = btnItem.getBoundingClientRect();

    btnActive.style.opacity = 1;
    btnActive.style.left = Math.round(btnBounding.left) - Math.round(itemBounding.left) + 'px';
    btnActive.style.width = btnItem.offsetWidth + 'px';
  };

  const bindComponent = (item) => {
    const btn = item.getElementsByClassName('js-btn-slide');

    [...btn].forEach((btnItem) => {
      if (btnItem.classList.contains('navigation__btn--active')) {
        activeState(item, btnItem);
      }
      btnItem.addEventListener('click', () => {
        console.log(btnItem.text);
        for (let i = 0; i < btn.length; i++) {
          btn[i].classList.remove('navigation__btn--active');
        };
        btnItem.classList.add('navigation__btn--active');
        activeState(item, btnItem);

        switch(btnItem.text){
          case "日":
            gantt.ext.zoom.setLevel("day");
            break;

          case "週":
            gantt.ext.zoom.setLevel("week");
            break;

          case "月":
            gantt.ext.zoom.setLevel("month");
            break;

          default:
            gantt.ext.zoom.setLevel("day");
            break;
        }
      })

      window.addEventListener('resize', () => activeState(item, btnItem));
    });
  };

  const init = () => {
    const rootEl = document.getElementsByClassName("js-btn-slide-container");
    [...rootEl].forEach((item) => bindComponent(item));
  };

  return {
    init
  };
})();

buttonSlide.init();

