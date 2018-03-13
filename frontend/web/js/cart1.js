/*
@功能：购物车页面js
@作者：diamondwang
@时间：2013年11月14日
*/

$(function(){

	
	//减少
	$(".reduce_num").click(function(){
		var amount = $(this).parent().find(".amount");
		if (parseInt($(amount).val()) <= 1){
			alert("商品数量最少为1");
		} else{
            // console.log(1);
			$(amount).val(parseInt($(amount).val()) - 1);

		}
		//小计
		var subtotal = parseFloat($(this).parent().parent().find(".col3 span").text()) * parseInt($(amount).val());
		$(this).parent().parent().find(".col5 span").text(subtotal.toFixed(2));
		//总计金额
		var total = 0;
		$(".col5 span").each(function(){
			total += parseFloat($(this).text());
		});

		$("#total").text(total.toFixed(2));
        edit($(this).closest('tr').attr('edit-id'),$(amount).val());


	});

	//增加
	$(".add_num").click(function(){
		var amount = $(this).parent().find(".amount");
		$(amount).val(parseInt($(amount).val()) + 1);
		//小计
		var subtotal = parseFloat($(this).parent().parent().find(".col3 span").text()) * parseInt($(amount).val());
		$(this).parent().parent().find(".col5 span").text(subtotal.toFixed(2));
		//总计金额
		var total = 0;
		$(".col5 span").each(function(){
			total += parseFloat($(this).text());
		});

		$("#total").text(total.toFixed(2));
        edit($(this).closest('tr').attr('edit-id'),$(amount).val());
	});

	//直接输入
	$(".amount").blur(function(){
		if (parseInt($(this).val()) < 1){
			alert("商品数量最少为1");
			$(this).val(1);
		}
		//小计
		var subtotal = parseFloat($(this).parent().parent().find(".col3 span").text()) * parseInt($(this).val());
		$(this).parent().parent().find(".col5 span").text(subtotal.toFixed(2));
		//总计金额
		var total = 0;
		$(".col5 span").each(function(){
			total += parseFloat($(this).text());
		});

		$("#total").text(total.toFixed(2));
        edit($(this).closest('tr').attr('edit-id'),$(this).val());

	});

	$('.delete').click(function () {
		var amount = 0;
        // edit($(this).closest('tr').attr('edit-id'),amount);
		var tr = $(this).closest('tr')
        $.get("/goods/edit.html",{goods_id:tr.attr('edit-id'),amount:amount},function (arr) {
			if (arr == 'success'){
                tr.remove();
			}
        });
    })

});
function edit(goods_id,amount){
    $.get("/goods/edit.html",{goods_id:goods_id,amount:amount});
}
