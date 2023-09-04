var currentPage = 1;
// normally post will be loaded
jQuery(document).ready(function () {
  jQuery.ajax({
    url: exporterajax.ajaxurl,
    type: "post",
    data: {
      action: "data_fetch_function",
      category: product_category,
      image_ratio: product_photo_type,
      edition: edition_ratio,
      photographer: myphotographer,
      image_id: image_id,
      paged: currentPage,
      p4d_session: new_p4d_title,
    },
    beforeSend: function () {},
    success: function (data) {
      jQuery("#products").html(data);
      jQuery("#load-more").show();
    },
    complete: function (data) {},
  });
});
// this function will be called on click load more
function load_more_function() {
  currentPage++; // Do currentPage + 1, because we want to load the next page
  var myphotographer = localStorage.getItem("myphotographer");
  var product_category = jQuery(
    "#child_category input[name='child_category']:checked"
  ).val();
  var product_photo_type = jQuery(
    ".aspect_ratio input[name='aspect_ratio']:checked"
  ).val();
  var edition_ratio = jQuery(
    ".edition_ratio input[name='edition_ratio']:checked"
  ).val();
  var new_p4d_title = "";
  let p4d_text_title = document.getElementById("p4d_title").innerHTML;
  new_p4d_title = p4d_text_title
    .replace("P4D : ", "")
    .replace("<script> ", "")
    .replace("document.write(p4d); ", "")
    .replace("</script>", "");
  var image_id = localStorage.getItem("cpm_image_id");
  var image_ratio = localStorage.getItem("cpm_image_ratio");
  jQuery.ajax({
    url: exporterajax.ajaxurl,
    type: "POST",
    dataType: "html",
    data: {
      action: "data_fetch_function",
      paged: currentPage,
      category: product_category,
      image_ratio: product_photo_type,
      edition: edition_ratio,
      photographer: myphotographer,
      p4d: new_p4d_title,
      image_id: image_id,
      image_ratio: image_ratio,
    },
    success: function (res) {
      jQuery("#products").append(res);
    },
  });
}
