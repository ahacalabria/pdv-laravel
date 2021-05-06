$(document).ready(function(){


  // $urlserver = window.location.host;
  $("#authentication").click(function(){
    var fdata = new FormData($('form')[0]);
    var route = $("form").attr("action");
    $.ajax({
      url:route,
      type: 'POST',
      data: fdata,
      dataType: 'json',
      contentType: false,
      processData: false,
      success: function(data){
        if(data.data.success){
          window.location.replace('/home');
        }else{
          $("#msg-auth").html(data.data.msg);
          $("#msg-auth-div").show();
        }
      },
      error: function(data){
        console.log(data);
        $("#msg-auth").html("Formulário em braco!");
        $("#msg-auth-div").show();
      }
    });
  });

  // if($('.dinheiro').length>0){
    $('.dinheiro').autoNumeric("init",{
      aSep: '.',
      aDec: ','
    });
  // }
  $("#msgOK, #msgERRO").hide();
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $("select#estados").change(function(){
    $.ajax({
      url: $urlserver+"/getcidades/"+$(this).val(),
      type: "get",
      success: function(data){
        data = JSON.parse(data);
        var $str = "<option value=''>Selecionar cidade</option>";
        $.each(data, function(key){
          $str += "<option value='"+data[key].id+"'>"+data[key].nome+"</option>";
        });
        $("select#cidades").chosen('destroy');
        $("select#cidades").html($str);
        $("select#cidades").chosen();
        // console.log();
        if($("#cidade_pessoa").length > 0) $("select#cidades").val($("#cidade_pessoa").val()).trigger("chosen:updated");
      },
      error: function(data){
        console.log(data);
      }
    });
  }).change();

  $(".addimposto").click(function(){
    var value = $("select.impostos option:selected").val();
    var theDiv = $(".is" + value);
    if(theDiv.hasClass("hidden"))
    theDiv.fadeOut().removeClass("hidden");
    set_price();
  });

  $("input#frete_produto, input#custo_produto, input#quantidade_estoque, input#agregado_produto").keyup(function() {
    set_price();
  });

  $(".selecionarimpostos").on('click','.remove', function (e) {
    e.preventDefault();
    $(this).parent().fadeIn(function() { $(this).addClass("hidden"); });
    set_price();
  });

  function getAllImpostos(){
    if($("select.impostos").length > 0){
      $.ajax({
        url: $urlserver+"/impostos/all",
        type: "get",
        success: function(data){
          data = JSON.parse(data);
          var $str = "", $str2 = "";
          if(data.length == 0) $str = "<option value='0'>Nenhum imposto cadastrado</option>";
          else{
            $str = "<option value=''>Selecione um tipo de despesa</option>";
            $.each(data, function(key){
              $str += "<option value='"+data[key].id+"'>"+data[key].nome+" ("+Number(data[key].valor).toLocaleString("pt-BR", {minimumFractionDigits: 2})+" %)"+" </option>";
              $str2 += '<h1 style="background-color: #9e9e9e" data-valor='+data[key].valor+' data-id="'+data[key].id+'" class="label label hidden is'+data[key].id+'">'+
              data[key].nome+' ('+Number(data[key].valor).toLocaleString("pt-BR", {minimumFractionDigits: 2})+' %)'+' <a href="" class="remove" rel="'+data[key].id+'">'+
              '<span style="color: #c62828;"class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></h1> ';
            });
          }
          $("select.impostos").html($str);
          $(".selecionarimpostos").html($str2);

          if($("#impostos_id").length>0) showImpostos();
        },
        error: function(data){
          console.log(data);
        }
      });
    }
  }
  if($("select.impostos").length > 0) getAllImpostos();
  function getAllUnidades(){
    if($("select#unidades").length > 0){
      $.ajax({
        url: $urlserver+"/unidades/all",
        type: "get",
        success: function(data){
          data = JSON.parse(data);
          var $str = "", $str2 = "";
          if(data.length == 0) $str = "<option value='0'>Nenhuma unidade cadastrada</option>";
          else{
            $str = "<option value=''>Selecione um tipo de unidade de medida</option>";
            $.each(data, function(key){
              $str += "<option value='"+data[key].id+"'>"+data[key].nome+"</option>";
            });
          }
          $("select#unidades").html($str);
          if($("#unidade_salva").length > 0) $("select#unidades").val($("#unidade_salva").val());
        },
        error: function(data){
          console.log(data.message);
        }
      });
    }
  }
  if($("select#unidades").length > 0) getAllUnidades();

  if($("select.tipopagamentos").length > 0){
    $.ajax({
      url: $urlserver+"/tipopagamentos/all",
      type: "get",
      success: function(data){
        data = JSON.parse(data);
        var $str = "", $str2 = "";
        if(data.length == 0) $str = "<option value=''>Nenhum tipo de pagamento cadastrado</option>";
        else{
          $str = "<option value=''>Selecione um tipo de pagamento</option>";
          $.each(data, function(key){
            $str += "<option value='"+data[key].id+"'>"+data[key].tipo+"</option>";
          });
        }
        $("select#tipopagamentos").html($str);
        $("select#tipopagamentos :eq(1)").prop('selected','selected');
      },
      error: function(data){
        console.log(data);
      }
    });
  }

  function getPessoaNameByID(id, linha){
    $.ajax({
      url: $urlserver+"/pessoa/"+id,
      type: "get",
      success: function(data2){
        data2 = JSON.parse(data2);
        return data2[0].nome;
      },
      error: function(data2){
        console.log(data2);
      }
    });
  }

  if($("select#clientes").length > 0){
    $.ajax({
      url: $urlserver+"/getpessoatipo/cliente",
      type: "get",
      success: function(data){
        data = JSON.parse(data);
        var $str = "", $str2 = "";
        if(data.length == 0) $str = "<option value=''>Nenhum cliente cadastrado</option>";
        else{
          $str = "<option value='0'>Selecione um cliente</option>";
          $.each(data, function(key){
            $str += "<option value='"+data[key].id+"'>"+data[key].nome+" "+data[key].sobrenome+" "+data[key].razao_social+"</option>";
          });
        }
        $("select#clientes").html($str);
      },
      error: function(data){
        console.log(data.message);
      }
    });
    $('select#clientes').select2(
    //   {
    //   ajax: {
    //     url: $urlserver+"/getpessoatipo/cliente",
    //     dataType: 'json',
    //     data: function (params) {
    //       var query = {
    //         search: params.term,
    //         // type: 'public'
    //       }
    
    //       // Query parameters will be ?search=[term]&type=public
    //       return query;
    //     },
    //     processResults: function (data) {
    //       // Tranforms the top-level key of the response object from 'items' to 'results'
    //       var list = data.map(function(obj){
    //         return {id: obj.id, text: obj.nome}
    //       });
    //       return {
    //         results: list
    //       };
    //     }
    //   }
    // }
    );
  }

  function getallprodutos($numero_linhas){
    //  $.ajax({
    //     url: $urlserver+"/produtos/all",
    //     type: "get",
    //     success: function(data){
    //         data = JSON.parse(data);
    //          var $str = "", $str2 = "";
    //         if(data.length == 0) $str = "<option value=''>Nenhum produto cadastrado</option>";
    //         else{
    //           $str = "<option value=''>Selecione um produto</option>";
    //           $.each(data, function(key){
    //             $str += "<option value='"+data[key].id+"'>"+data[key].titulo+" - "+data[key].id+"</option>";
    //           });
    //         }
    //         if($numero_linhas == "*")
    //           $("select.produtos").html($str);
    //         else
    //           $("table#table_produtos > tbody tr:eq("+$numero_linhas+")").find('select').html($str);
    //     },
    //     error: function(data){
    //       console.log(data.message);
    //     }
    // });
    $.ajax({
      url: $urlserver+"/produtos/all",
      type: "get",
      success: function(data){
        data = JSON.parse(data);
        var $str = "", $str2 = "";
        if(data.length == 0){
          $str = "<option value=''>Nenhum produto cadastrado</option>";
          //  $str2 = "<option value=''>Nenhum produto cadastrado</option>";
        }
        else{
          $str = "<option value=''>Selecione um produto pela descrição</option>";
          //  $str2 = "<option value=''>Selecione um produto pelo código</option>";
          $.each(data, function(key){
            if(!data[key].desabilitar) $str += "<option value='"+data[key].id+"'>"+data[key].titulo+"</option>";
            //  $str2 += "<option value='"+data[key].id+"'>"+data[key].id+"</option>";
          });
        }
        if($numero_linhas == "*"){
          //  $("select.produtos_codigo_only").html($str2);
          $("select.produtos_descricao_only").html($str);
        }else{
          //  $("select.produtos_codigo_only").html($str2);
          $("select.produtos_descricao_only").html($str);
        }
        //  $("table#table_produtos > tbody tr:eq("+$numero_linhas+")").find('select').html($str);
        // $('.produtos_codigo_only').chosen({enable_split_word_search:true,search_contains: false,no_results_text:"Código não encontrado!"});
        $('.produtos_descricao_only').chosen({search_contains: true});//{search_contains: true,no_results_text:"Descrição não encontrada!"});
      },
      error: function(data){
        console.log(data.message);
      }
    });
  }

  function getallbanks(){
    $.ajax({
      url: $urlserver+"/banks",
      type: "get",
      success: function(data){
        var $str = "";
        if(data.length == 0) $str = "<option value=''>Nenhum banco cadastrado</option>";
        else{
          $str = "<option value=''>Selecione um banco</option>";
          $.each(data, function(key){
            $str += "<option value='"+data[key].id+"'>"+data[key].title+"</option>";
          });
        }
        // $("select.banks").val(1);
        $("select.banks").html($str);
      },
      error: function(data){
        console.log(data);
      }
    });
  }

  if($("select.produtos").length > 0 || $(".produtos_descricao_only").length > 0){
    getallprodutos("*");
  }

  function getAllFornecedores(){
    if($("select#fornecedores").length > 0){
      $.ajax({
        url: $urlserver+"/getpessoatipo/fornecedor",
        type: "get",
        success: function(data){
          data = JSON.parse(data);
          var $str = "", $str2 = "";
          if(data.length == 0) $str = "<option value=''>Nenhum fornecedor cadastrado</option>";
          else{
            $str = "<option value=''>Selecione um fornecedor</option>";
            $.each(data, function(key){
              if(data[key].razao_social == "")
              $str += "<option value='"+data[key].id+"'>"+data[key].nome+' '+data[key].sobrenome+"</option>";
              else
              $str += "<option value='"+data[key].id+"'>"+data[key].razao_social+"</option>";
            });
          }
          $("select#fornecedores").html($str);
        },
        error: function(data){
          console.log(data);
        }
      });
      $('select#fornecedores').select2({
        // ajax: {
        //   url: $urlserver+"/getpessoatipo/fornecedor",
        //   dataType: 'json',
        //   data: function (params) {
        //     var query = {
        //       search: params.term,
        //       // type: 'public'
        //     }
      
        //     // Query parameters will be ?search=[term]&type=public
        //     return query;
        //   },
        //   processResults: function (data) {
        //     // Tranforms the top-level key of the response object from 'items' to 'results'
        //     var list = data.map(function(obj){
        //       return {id: obj.id, text: (obj.nome+obj.razao_social)}
        //     });
        //     return {
        //       results: list
        //     };
        //   }
        // }
      });
    }
  }
  getAllFornecedores();

  function getAllFuncionarios(){
    if($("select#funcionarios").length > 0){
      $.ajax({
        url: $urlserver+"/getpessoatipo/funcionario",
        type: "get",
        success: function(data){
          data = JSON.parse(data);
          var $str = "", $str2 = "";
          if(data.length == 0) $str = "<option value=''>Nenhum funcionário cadastrado</option>";
          else{
            $str = "<option value=''>Selecione um funcionário</option>";
            $.each(data, function(key){
              if(data[key].razao_social == "")
              $str += "<option value='"+data[key].id+"'>"+data[key].nome+' '+data[key].sobrenome+"</option>";
              else
              $str += "<option value='"+data[key].id+"'>"+data[key].razao_social+"</option>";
            });
          }
          $("select#funcionarios").html($str);
        },
        error: function(data){
          console.log(data);
        }
      });
    }
  }
  getAllFuncionarios();

  $("select#unidades").change(function(){
    $("#spanUnidade").text( (($(this).val() === null) ? ('?') : ($("select#unidades option:selected").text())));
  }).change();

  $("select#tipo").change(function(){
    if($(this).val() == 'f'){
      $("#nome-fantasia").css('display','none');
      $("#nome-responsavel").css('display','none');
      $("#telefone-responsavel").css('display','none');
      $("#razao-social").css('display','none');
      $("#nome").css('display','block');
      $("#sobrenome").css('display','block');
      $("#cnpj").css('display','none');
      $("#cpf").css('display','block');
      $("#ie").css('display','none');
      $("#rg").css('display','block');
      $("#data-nascimento").css('display','block');
      $("#sexo").css('display','block');
      $("#site").css('display','none');
    }else if($(this).val() == 'j') {
      $("#nome-fantasia").css('display','block');
      $("#nome-responsavel").css('display','block');
      $("#telefone-responsavel").css('display','block');
      $("#razao-social").css('display','block');
      $("#data-nascimento").css('display','none');
      $("#sexo").css('display','none');
      $("#nome").css('display','none');
      $("#sobrenome").css('display','none');
      $("#cnpj").css('display','block');
      $("#cpf").css('display','none');
      $("#ie").css('display','block');
      $("#rg").css('display','none');
      $("#site").css('display','block');
    }
  }).change();

  if( $(".js-example-basic-single").length >0 ) $(".js-example-basic-single").select2();
  if($("select#pessoa-nome").length > 0){
    $.ajax({
      url: $urlserver+"/getpessoatipo/funnologin",
      type: "get",
      success: function(data){
        data = JSON.parse(data);
        var $str = "<option value=''> -- selecione -- </option>";
        $.each(data, function(key){
          $str += "<option value='"+data[key].id+"' email='"+data[key].email+"'>"+data[key].nome+"</option>";
        });
        $("select#pessoa-nome").html($str);

      },
      error: function(data){
        console.log(data);
      }
    });
  }

  $("select#pessoa-nome").change(function(){
    $("#email").val($("select#pessoa-nome option:selected").attr('email'));
    $("#user_name").val($("select#pessoa-nome option:selected").text());
  }).change();

  $('.dropdown-toggle').dropdown();

  $("body").on("click", "button.deletar", function(d){
    if(confirm('Confirmar a exclusão?')){

      var token = $('meta[name="csrf-token"]').attr('content');
      var route = $(this).attr('url');
      $.ajax({
        url:route,
        type: 'post',
        data: {_method: 'delete', _token :token},
        success: function(data){
          if(data.match("^<!DOCTYPE html>")){
            alert('Ação não permitida!!!');
          }else{
            $("#msgOK").text("Operação deletar realizada com sucesso!");
            $("#msgOK").show();
            setTimeout(function () {
              $("#msgOK").hide();
            }, 10000);
            table.ajax.reload();
          }
        },
        error: function(data){
          console.log(data);
          $("#msgERRO").text("Erro ao deletar!");
          $("#msgERRO").show();
          setTimeout(function () {
            $("#msgERRO").hide();
          }, 10000);
          table.ajax.reload();
        }
      });
    }
  });

  function set_price(){
    var $val_perc = 0.0;
    var valor = getObjValueWithoutMask($("#custo_produto"));
    $("#selecionarimpostos").find('h1').each(function(){
      if(!$(this).hasClass('hidden')) $val_perc+=parseFloat($(this).attr('data-valor'));
    });
    var valor = (valor/(1-($val_perc/100)));//imposto
    // console.log("valor_impo: "+valor_impostos);
    // if(valor<0) valor=0;
    var valor_frete =  0;//getObjValueWithoutMask($("#frete_produto")); FRETE ZERO
    var frete = valor_frete == "" ? 0 : valor_frete;
    var valor_frete_final = (frete == 0) ? 0 : ($("#quantidade_estoque").val() == "" ? 1 : $("#quantidade_estoque").val())
    if(valor_frete_final != 0) valor_frete_final = frete/valor_frete_final;
    var valor_agregado = getObjValueWithoutMask($("#agregado_produto")); //pegando o valor agregado do campo de texto
    var valor = valor+parseFloat(valor_frete_final);

    var b = valor_agregado; //porcetagem em cima
    var a = valor;//custo do produto
    if(b>=100){ /* CALCULO */
      b=b/parseFloat(100+parseFloat(b));
    }else{
      b=b/100;
    }
    // console.log("a: "+a);
    // console.log("b: "+b);
    // console.log((a/parseFloat(1-parseFloat(b))));
    var preco_venda = (a/parseFloat(1-b));
    setObjValMoneyMask($("#preco_produto"),preco_venda); //setando preco final na caixa de texto
  }

  $("body").on("click", "button.salvar-produto", function(d){
    var token = $('meta[name="csrf-token"]').attr('content');
    var route = $(this).attr('url');
    var $impostosList = Array();
    $("#preco_produto_hidden").val(getObjValueWithoutMask($("#preco_produto")));
    $("#custo_produto_hidden").val(getObjValueWithoutMask($("#custo_produto")));
    $("#frete_produto_hidden").val(getObjValueWithoutMask($("#frete_produto")));
    $("#agregado_produto_hidden").val(getObjValueWithoutMask($("#agregado_produto")));
    $("#selecionarimpostos").find('h1').each(function(){
      if(!$(this).hasClass('hidden')) $impostosList.push($(this).attr('data-id'));
    });
    $("#impostos_id").val($impostosList.toString());
    $('form').submit();

    //  $.ajax({
    //    url:route,
    //    type: 'post',
    //    data: form,
    //    success: function(data){
    //      alert();
    //      console.log(data);
    //     },
    //     error: function(data){
    //       console.log(data);
    //     }
    // });
  });

  $("body").on("click", "button.editar-produto", function(d){
    var token = $('meta[name="csrf-token"]').attr('content');
    var route = $(this).attr('url');
    var $impostosList = Array();
    $("#preco_produto_hidden").val(getObjValueWithoutMask($("#preco_produto")));
    $("#custo_produto_hidden").val(getObjValueWithoutMask($("#custo_produto")));
    $("#frete_produto_hidden").val(getObjValueWithoutMask($("#frete_produto")));
    $("#agregado_produto_hidden").val(getObjValueWithoutMask($("#agregado_produto")));
    $("#selecionarimpostos").find('h1').each(function(){
      if(!$(this).hasClass('hidden')) $impostosList.push($(this).attr('data-id'));
    });
    $("#impostos_id").val($impostosList.toString());
    $('form').submit();
    //  $.ajax({
    //    url:route,
    //    type: 'post',
    //    data: form,
    //    success: function(data){
    //      console.log(data);
    //     },
    //     error: function(data){
    //       console.log(data);
    //     }
    // });
  });

  function showImpostos(){
    var list = [];
    list = $("#impostos_id").val().replace(/ /g,'').split(',');
    $.each(list, function(i){
      var theDiv = $(".is" + list[i]);
      if(theDiv.hasClass("hidden"))
      theDiv.fadeOut().removeClass("hidden");
    });
  }

  //  function evento_select(){
  //    $("table#table_produtos > tbody > tr > td").on('change', '.produtos', function(){
  //       var produto_id = $(this).val();
  //       var row = $(this);
  //       $.ajax({
  //          url: $urlserver+"/produtos/"+produto_id,
  //          type: "get",
  //          success: function(data){
  //              data = JSON.parse(data);
  //              var subtotal=0.0;
  //              row.parent().siblings().each(function(i){
  //                if(i==0)
  //                 $(this).text(data[0].descricao);
  //                else if(i==1){
  //                 subtotal = getObjValueWithoutMask($(this).find('input'));
  //                 $(this).find('input').focus();
  //                 var span = $(this).find('span');
  //                 $.ajax({
  //                    url: $urlserver+"/unidades/"+data[0].unidade_id,
  //                    type: "get",
  //                    success: function(data2){
  //                      data2 = JSON.parse(data2);
  //                      span.text(data2[0].sigla);
  //                    },error: function(data2){
  //                      console.log("error: tentando pegar uma unidade: "+data2);
  //                    }
  //                  });
  //               }else if(i==2) {
  //                  setObjValMoneyMask($(this).find('input'),parseFloat(data[0].preco));
  //                  subtotal *= parseFloat(data[0].preco);
  //                }
  //                else if(i==3)
  //                 setObjValMoneyMask($(this).find('input'),parseFloat(subtotal));
  //              });
  //              calcular_total_venda();
  //             //  $(this).find('input.quantidades').first().focus();
  //          },
  //          error: function(data){
  //            console.log(data);
  //          }
  //      });
  //   });
  //  }
  //  evento_select();
  // var control_change_new = true;
  function evento_select(){
    $(".inserir_item").on('click', function(){
      if($(this).data('etiquetas')){
        var produto_id = $('.produtos_codigo_only').val();
        var $str = "";
        $.ajax({
          url: $urlserver+"/produtos/"+produto_id,
          type: "get",
          success: function(data){
            data = JSON.parse(data).produtos;
            if(data.length!==0){
              var curr_id = data[0].id;
              var repeated_prod = 0;
              if($("#table_produtos tbody tr").each(function(i){
                var value_temp_prod_curr = $(this).find('td > input').first().val()
                if(curr_id==value_temp_prod_curr)
                  repeated_prod++;
              }))
              if(repeated_prod>0){
                alert((repeated_prod==1)?"Produto já adicionado":"Produto adicionado "+repeated_prod+" vezes!")
              } 
              // else {
                $str = '<tr>'+
                '<td class="col-sm-1">'+
                '<input class="form-control" name="codigo_produto[]" type="text" readonly="readonly" value="'+data[0].id+'">'+
                '</td>'+
                '<td class="col-sm-4">'+data[0].titulo+'</td>'+
                '<td class="col-sm-2">'+
                '<div class="input-group">'+
                '<input type="text" class="form-control dinheiro quantidades" name="quantidade_produto[]" placeholder="0,00">'+
                '<span class="input-group-addon">'+data[0].unidade.sigla+' </span>'+
                '</div>'+
                '<p class="help" style="font-size: xx-small;text-align: -webkit-center;color: #f00;"></p>'+
                '</td>'+
                '<td class="col-sm-1"><button class="btn btn-danger delete-row-produto" type="button"><span class="glyphicon glyphicon-remove"></span></button></td>'+
                '</tr>';

                // if($("table#table_produtos > tbody").children().length == 1 && control_change_new){
                //   $("table#table_produtos > tbody").html($str);
                //   control_change_new = false;
                // }else
                $("table#table_produtos > tbody").append($str);

                $("table#table_produtos > tbody").find('input.quantidades').last().focus();
                evento_calcular_qtd2();
                evento_deletar();
                $(".produtos_codigo_only").val('');//.trigger("chosen:updated");
                $(".produtos_descricao_only").val('').trigger("chosen:updated");
              // }
            }else{
              alert("Produto não encontrado!");
              $(".produtos_codigo_only").val("").focus();
            }
          },
          error: function(data){
            alert("Produto não encontrado!");
            $(".produtos_codigo_only").val("").focus();
            console.log(data);
          }
        });
      }else{
        var produto_id = $('.produtos_codigo_only').val();
        var $str = "";
        $.ajax({
          url: $urlserver+"/produtos/"+produto_id,
          type: "get",
          success: function(data){
            // console.log({data})
            data = JSON.parse(data).produtos;
            if(data.length!==0){
              var curr_id = data[0].id;
              var repeated_prod = 0;
              if($("#table_produtos tbody tr").each(function(i){
                var value_temp_prod_curr = $(this).find('td > input').first().val()
                if(curr_id==value_temp_prod_curr)
                  repeated_prod++;
              }))
              if(repeated_prod>0){
                 alert((repeated_prod==1)?"Produto já adicionado":"Produto adicionado "+repeated_prod+" vezes!")
              } //else{
                $str = '<tr>'+
                '<td class="col-sm-1">'+
                '<input class="form-control" name="codigo_produto" type="text" disabled="true" value="'+data[0].id+'">'+
                '</td>'+
                '<td class="col-sm-4">'+data[0].titulo+'</td>'+
                '<td class="col-sm-2">'+
                '<div class="input-group">'+
                '<input type="text" class="form-control dinheiro quantidades" placeholder="0,00">'+
                '<span class="input-group-addon">'+data[0].unidade.sigla+' </span>'+
                '</div>'+
                '<p class="help" style="font-size: xx-small;text-align: -webkit-center;color: #f00;"></p>'+
                '</td>'+
                '<td class="col-sm-2">'+
                '<div class="input-group">'+
                '<span class="input-group-addon">R$</span>'+
                '<input type="text" class="form-control dinheiro valor_item" '+(($level=="vendedor")?' readonly="readonly" ':'')+' placeholder="0,00" value="'+data[0].preco+'">'+
                '</div>'+
                '</td>'+
                '<td class="col-sm-2 subtotal">'+
                '<div class="input-group">'+
                '<span class="input-group-addon">R$</span>'+
                '<input id="teste_centavos" type="text" class="form-control dinheiro" placeholder="0,00" disabled="true" value="0.0">'+
                '</div>'+
                '</td>'+
                '<td class="col-sm-1"><button class="btn btn-danger delete-row-produto" type="button"><span class="glyphicon glyphicon-remove"></span></button></td>'+
                '</tr>';
    
                // if($("table#table_produtos > tbody").children().length == 1 && control_change_new){
                //   $("table#table_produtos > tbody").html($str);
                //   control_change_new = false;
                // }else
                $("table#table_produtos > tbody").append($str);
    
                $('.dinheiro').autoNumeric("init",{
                  aSep: '.',
                  aDec: ','
                });
                $("table#table_produtos > tbody").find('input.quantidades').last().focus();
                evento_calcular_qtd();
                evento_deletar();
                $(".produtos_codigo_only").val('');//.trigger("chosen:updated");
                $(".produtos_descricao_only").val('').trigger("chosen:updated");
            // }
            }else{
              alert("Produto não encontrado!");
              $(".produtos_codigo_only").val("").focus();
            }
          },
          error: function(data){
            console.log(data);
          }
        });
      }
    });
  }
  evento_select();
  function saveHistoricoBusca(produto){
    
  }
  function evento_deletar(){
    $("table#table_produtos > tbody > tr > td").on('click', '.delete-row-produto', function(){
      if($(this).parent().parent().siblings().length== 0)
        alert("Uma venda deve conter pelo menos um produto!");
      else{
        if(confirm('Desejas realmente retirar esse produto?')){
          var linha_tabela = $( this ).parent().parent();
          linha_tabela.remove();
          calcular_total_venda();
        }
      }
      calcular_total_venda();
    });
  }
  evento_deletar();

  $("#add_produto_row").click(function(){
    console.log($level);
    var inner_html = '<tr><td class="col-sm-3"><select class="form-control js-example-basic-single produtos" name="produto_id"></select>'+
    '</td><td class="col-sm-2"></td><td class="col-sm-2"><div class="input-group"><input type="text" class="form-control dinheiro quantidades" placeholder="0,00">'+
    '<span class="input-group-addon"> </span></div></td><td class="col-sm-2"><div class="input-group">'+
    '<span class="input-group-addon">R$</span><input type="text" class="form-control dinheiro valor_item" '+
    ' placeholder="0,00"></div></td><td class="col-sm-2 subtotal"><div class="input-group"><span class="input-group-addon">R$</span>'+
    '<input type="text" class="form-control dinheiro" placeholder="0,00" disabled="true"></div>'+
    '</td><td class="col-sm-1"><button class="btn btn-danger delete-row-produto" type="button"><span class="glyphicon glyphicon-remove"></span></button></td></tr>';
    $("table#table_produtos > tbody").append(inner_html);
    var numero_linhas = $("table#table_produtos > tbody").children().length;
    getallprodutos(--numero_linhas);
    $(".js-example-basic-single").select2();
    evento_select();
    evento_calcular_qtd();
    evento_deletar();
    $('.dinheiro').autoNumeric("init",{
      aSep: '.',
      aDec: ','
    });
  });

  function evento_calcular_qtd(){
    $("table#table_produtos > tbody > tr > td > div > input.quantidades, table#table_produtos > tbody > tr > td > div > input.valor_item ").keyup(function(){
      var no_value = true;
      var qtd = getObjValueWithoutMask($(this));
      var row = $(this).parent();
      var subtotal=qtd;
      row.parent().siblings().each(function(i){
        if(i==2){
          var temp = getObjValueWithoutMask($(this).find('input'));
          if(temp!=0){
            subtotal *= parseFloat(temp);
            no_value = false;
          }else no_value = true;
        }else if(i==3){
          if(!no_value)
          setObjValMoneyMask($(this).find('input'),parseFloat(subtotal));
        }
        calcular_total_venda();
      });
    });
  }
  evento_calcular_qtd();

  function evento_calcular_qtd2(){
    $("table#table_produtos > tbody > tr > td > div > input.quantidades, table#table_produtos > tbody > tr > td > div > input.valor_item ").keyup(function(){
      var no_value = true;
      var qtd = getObjValueWithoutMask($(this));
      var row = $(this).parent();
      var subtotal=qtd;
      row.parent().siblings().each(function(i){
        if(i==2){
          var temp = getObjValueWithoutMask($(this).find('input'));
          if(temp!=0){
            // subtotal *= parseFloat(temp);
            no_value = false;
          }else no_value = true;
          // }else if(i==3){
          // if(!no_value)
          // setObjValMoneyMask($(this).find('input'),parseFloat(subtotal));
        }
        calcular_total_venda2();
      });
    });
  }

  function calcular_total_venda(){
    var subtotal = 0;
    $("table#table_produtos > tbody").children().each(function(i){
      $(this).children().each(function(j){
        if(j==4) {
          var temp = getObjValueWithoutMask($(this).find('input'));
          if(!(temp === ""))
          subtotal+=parseFloat(temp);
        }
      });
    });
    setObjValMoneyMask($("#valor_total_venda"),subtotal);
    var desconto = getObjValueWithoutMask($("#valor_desconto"));
    var frete = getObjValueWithoutMask($("#valor_frete"));
    var tipo_desconto = $("#tipo_desconto").val();
    if(tipo_desconto == 'p') desconto = parseFloat(subtotal*(desconto/100.0));
    // if() fazer verificação
    var valor_total = (subtotal-parseFloat(desconto))+parseFloat(frete);
    setObjValMoneyMask($("#valor_total_liquido"),valor_total);
  }

  function calcular_total_venda2(){
    var subtotal = 0;
    $("table#table_produtos > tbody").children().each(function(i){
      $(this).children().each(function(j){
        if(j==2) {
          var temp = $(this).find('input').val();
          if(!(temp === ""))
          subtotal+=parseFloat(temp);
        }
      });
    });
    setObjValMoneyMask($("#valor_total_venda"),subtotal);
    // var desconto = getObjValueWithoutMask($("#valor_desconto"));
    // var frete = getObjValueWithoutMask($("#valor_frete"));
    // var tipo_desconto = $("#tipo_desconto").val();
    // if(tipo_desconto == 'p') desconto = parseFloat(subtotal*(desconto/100.0));
    // // if() fazer verificação
    // var valor_total = (subtotal-parseFloat(desconto))+parseFloat(frete);
    // setObjValMoneyMask($("#valor_total_liquido"),valor_total);
  }

  $("input#valor_frete, input#valor_desconto").keyup(function() {
    calcular_total_venda();
  });

  $("#bt_tipo_desconto_d").click(function(){
    $("#span_tipo_desconto").text('R$');
    $("#tipo_desconto").val('d');
    calcular_total_venda();
    $("#tipopagamentos").trigger("change");
  });
  $("#bt_tipo_desconto_p").click(function(){
    $("#span_tipo_desconto").text('%');
    $("#tipo_desconto").val('p');
    calcular_total_venda();
    $("#tipopagamentos").trigger("change");
  });

  $('.dinheiro').autoNumeric("init",{
    aSep: '.',
    aDec: ','
  });
  $('.datetimepicker-now').datetimepicker({
    locale: 'pt-BR',
    allowInputToggle: true,
    date: new Date()
  });
  $('#datetimepicker').datetimepicker({
    locale: 'pt-BR',
    allowInputToggle: true,
    date: new Date()
  });
  $('#datetimepickerEDIT').datetimepicker({
    locale: 'pt-BR',
    allowInputToggle: true
  });
  function setDatePickerVencimento(){
    if($('.datepickervecimento').length > 0){
      $('.datepickervecimento').datetimepicker({
        locale: 'pt-BR',
        format: 'L',
        allowInputToggle: true
      });
    }
  }

  function getObjValueWithoutMask(obj){
    var val = obj.autoNumeric('get');
    return val;
  }
  function setObjValMoneyMask(obj,val){
    if(val >= 0)
    obj.autoNumeric('set', val);
    else
    obj.autoNumeric('set', 0);
  }

  $("#confirmarVendaBt").on("click",function(){
    var empty_row = false;
    var lista_produtos_id = [];
    var lista_quantidades = [];
    var lista_precos = [];
    var cliente_nome = $("#clientes > option:selected").text();
    if(cliente_nome=="") cliente_nome = $("#cliente_nome_sel option:selected").text();
    var vendedor_nome = $("#vendedor_nome").val();
    var data_venda = $("#data_venda").val();
    var modal_inner_content = "<p>Cliente: "+cliente_nome+"<br>Vendedor: "+vendedor_nome+"<br>"+
    "Data: "+data_venda+"</p>";
    var produtos_info = "<p>Produtos:<br>";
    $("table#table_produtos > tbody").children().each(function(i){
      $(this).children().each(function(j){
        if(j==0){
          var prodID = $(this).find('input').val();
          //  var prodTitulo = $(this).find('select option:selected').text();
          //  if(prodID===undefined) {
          //    prodID = $(this).find('input').attr('prodid');
          //    prodTitulo = $(this).find('input').val()
          //  }
          //  if(prodID === "") {
          //    empty_row = true;
          //    return;
          //  }
          lista_produtos_id.push(prodID);

        }else if(j==1){
          var prodTitulo = $(this).text();
          produtos_info+= " "+prodTitulo+"";
        }else if(j==2 && !empty_row){
          produtos_info+= " "+$(this).find('input').val()+"";
          lista_quantidades.push(getObjValueWithoutMask($(this).find('input')));
          produtos_info+= " "+$(this).find('span').text()+" x";
        }else if(j==3 && !empty_row){
          produtos_info+= " "+$(this).find('input').val()+" =";
          lista_precos.push(getObjValueWithoutMask($(this).find('input')));
        }else if(j==4 && !empty_row)
        produtos_info+= " "+$(this).find('input').val()+" ";
      });
      (!empty_row) ? produtos_info+="<br>" : produtos_info=produtos_info;
    });
    //  var data_temp = new Date(dateFormat($('#data_venda').value,"yyyy-mm-dd HH:MM:ss"));
    //  var data_temp = new Date(dateFormat($("#data_venda").val(),"yyyy-mm-dd HH:MM:ss"));
    $("#data_venda_hidden").val($("#data_venda").val());
    $("#produtos_id").val(lista_produtos_id);
    $("#quantidades").val(lista_quantidades);
    $("#precos").val(lista_precos);
    $("#valor_total_hidden").val(getObjValueWithoutMask($("#valor_total_venda")));
    $("#valor_desconto_hidden").val(getObjValueWithoutMask($("#valor_desconto")));
    $("#valor_frete_hidden").val(getObjValueWithoutMask($("#valor_frete")));
    $("#valor_total_liquido_hidden").val(getObjValueWithoutMask($("#valor_total_liquido")));

    (produtos_info=="<p>Produtos:<br>") ? modal_inner_content+="<p>Nenhum produto selecionado!</p>" : modal_inner_content+=produtos_info;
    modal_inner_content+= "<br>TOTAL: R$ "+$("#valor_total_venda").val()+"<br>";
    var tipo_desconto = $("#tipo_desconto").val();
    if(tipo_desconto==="d")
    modal_inner_content+= "DESCONTO: R$ "+$("#valor_desconto").val()+"<br>";
    else if(tipo_desconto==="p")
    modal_inner_content+= "DESCONTO: % "+$("#valor_desconto").val()+"<br>";
    modal_inner_content+= "FRETE: R$ "+$("#valor_frete").val()+"<br>";
    modal_inner_content+= "TOTAL LÍQUIDO: R$ "+$("#valor_total_liquido").val()+"<br>";
    $("#modal-conteudo").html(modal_inner_content);
    content = modal_inner_content.replace(/<br>/g,'\r\n');
  });

  var content = "";

  function getOriginalDateFormat(date){
    return dateFormat(date, "yyyy-mm-dd HH:MM:ss");
  }
  function sendtoprinter($url, $id){
    $.ajax({
      url: $url,
      type: 'get',
      success: function(data){
        console.log(data);
      },
      error: function(data){
        console.log(data);
      }
    });
  }

  $("body").on('click','.cancelar-venda', function(){
    if(confirm('Desejas realmente cancelar esta venda?')){
      $(this).find("span").attr("class","glyphicon glyphicon-repeat");
      $(this).find("span").css(
        "animation", "roll 3s infinite"
      )
      var token = $('meta[name="csrf-token"]').attr('content');
      var route = $(this).attr('url');
      $.ajax({
        url:route,
        type: 'post',
        data: {_method: 'delete', _token :token},
        success: function(data){
          console.log(data);
          if(data.match("^<!DOCTYPE html>")){
            alert('Ação não permitida!!!');
          }else{
            alert('Venda cancelada com sucesso!');
            window.location.reload(true);
          }
        },
        error: function(data){
          console.log(data);
        }
      });
    }
  });
  $("body").tooltip({ selector: '[data-toggle=tooltip]' });

  $("#finalizarVenda").click(function(e){
    if($("#clientes").val() == 0) alert('ERRO: ESTÁ FALTANDO O CLIENTE!');
    else{
      $(this).html("Carregando...");
      $(this).attr("disabled","disabled");
      var fdata = new FormData($('#formulario')[0]);
      var route = $("#formulario").attr("action");
      console.log(route);
      $.ajax({
        url:route,
        type: 'POST',
        data: fdata,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function(data){
          if(data.data.success){
            $('#modalConfirmarVenda').modal('hide');
            alert(data.data.msg);
            // sendtoprinter($urlserver+"/printcupom/"+data.data.id);
            window.location.replace('/vendas');
          }else{
            alert(data.data.msg);
          }
        },
        error: function(data){
          console.log(data);
        }
      });
    }
  });

  $('body').on('click','.print-cupom,.print-cupom2', function(){
    console.log('sending...');
    sendtoprinter($(this).attr('url'));
  });

  $("body").on('click','.view-venda',function(e){
    var modal_inner_content = "";
    var route = $(this).attr("url");
    $.ajax({
      url:route,
      type: 'get',
      success: function(venda){
        venda = JSON.parse(venda);
        modal_inner_content += "Cód. Venda: "+venda.id;
        modal_inner_content += "<br>Cód. Vendedor(a): "+venda.vendedor.id;
        modal_inner_content += "<br>Nome Vendedor(a): "+venda.vendedor.nome;
        if(venda.conferente!=null) modal_inner_content += "<br>Nome Conferente(a): "+venda.conferente.nome;
        modal_inner_content += "<br><br>Data/Hora: "+venda.data_venda;
        modal_inner_content += "<br>Cód. Cliente: "+venda.cliente.id;
        modal_inner_content += "<br>Nome Cliente: "+venda.cliente.nome+venda.cliente.razao_social;
        modal_inner_content += "<br><br>Cód. Desc. Qt. V. UN";
        $.each(venda.produtos, function(i){
          modal_inner_content += "<br>"+venda.produtos[i].id;
          modal_inner_content += " "+venda.produtos[i].titulo;
          modal_inner_content += " "+parseFloat(venda.produtos[i].pivot.quantidade);
          modal_inner_content += " x R$"+parseFloat(venda.produtos[i].pivot.preco);
          modal_inner_content += " "+venda.produtos[i].pivot.unidade_nome;
        });
        modal_inner_content += "<br><br>Subtotal: R$"+venda.valor_total;
        if(venda.tipo_desconto == "d")
        modal_inner_content += "<br>Desconto: R$"+venda.valor_desconto;
        else{
          modal_inner_content += "<br>Desconto: R$"+(venda.valor_desconto)*(venda.valor_total)/100.0;
        }
        modal_inner_content += "<br>Frete: R$"+venda.valor_frete;
        modal_inner_content += "<br>Total: R$"+venda.valor_liquido;
        $("#modal-conteudo").html(modal_inner_content);
        $("#modalViewVenda").modal('show');
      },
      error: function(data){
        console.log(data);
      }
    });
  });

  $("#parcela").change(function(){
    var parcelas = $(this).val();
    updateParcelas(parcelas);
  }).change();

  function updateParcelas(parcelas){
    var valor_total = getObjValueWithoutMask($("#valor_total_liquido"));
    var juros = getObjValueWithoutMask($("#valor-juros"))/100;
    valor_total = parseFloat(valor_total)+parseFloat(valor_total*juros);
    //  alert(valor_total);
    var valor_parcela = (valor_total/parcelas).toFixed(2);
    var content = "";
    for(var i=0; i<parcelas; i++){
      content+='<hr style="clear:both;" /><div class="col-sm-12 parcela-row"><div class="form-group col-sm-1"><h4 style="">'+(i+1)+'/'+parcelas+'</h4></div><div class="form-group col-sm-3">'+
      '<label>Vencimento </label>'+
      '<div class="form-group">'+
      '<div class="input-group datepickervecimento" id="datavencimento'+i+'">'+
      '<input type="text" class="form-control" id="datavencimentoinput'+i+'"/>'+
      '<span class="input-group-addon btn">'+
      '<span class="glyphicon glyphicon-calendar"></span>'+
      '</span>'+
      '</div>'+
      '</div>'+
      '</div>'+
      '<div class="form-group col-sm-3">'+
      '<label>Valor da parcela </label>'+
      '<div class="input-group col-sm-12">'+
      '<span class="input-group-addon">R$</span>'+
      '<input id="parcelasvalor'+i+'" class="form-control dinheiro" type="text" value="'+valor_parcela+'"/>'+
      '</div>'+
      '</div>'+
      '<div class="form-group col-sm-3">'+
      '<label>Obeservações desta parcela </label>'+
      '<input id="parcelasobs'+i+'" class="form-control" type="text""/>'+
      '</div>';
      if($hasCheque){
        content+='<div class="form-group col-sm-2 cheque-opcoes" idparcela="'+(i+1)+'">'+
        '<label class="col-sm-12">Cheque </label> '+
        '<button type="button" class="btn btn-success upload-cheque"><span class="glyphicon glyphicon-upload"></span> Enviar arquivo</button>'+
        //  '<input class="form-control" type="text" name="obs"/>'+
        '</div>'
      }
      content+='</div></div>';
    }
    $("#parcelas-content").html(content);
    $('.dinheiro').autoNumeric("init",{
      aSep: '.',
      aDec: ','
    });
    setDatePickerVencimento();
    for(var i=0; i<parcelas; i++){
      $day = $("#dia_vencimento").val() == "" ? "1" :  $("#dia_vencimento").val();
      $("#datavencimento"+i).data("DateTimePicker").date(moment(new Date()).add(i+1, 'M').format('DD/MM/YYYY'));
    }
  }

  var $hasCheque = true;

  $("#tipopagamentos").change(function(){
    var textselect = $("select#tipopagamentos option:selected").text();
    $("#condicao_pagamento_valor").html('<div class="alert alert-success" role="alert"><b>Tipo pagamento: </b>'+textselect+'</div>');
    $("#tipopagamentonome").val(textselect);
    $("#div-valor-recebido").hide();
    $("#div-valor-troco").hide();
    if($(this).val()==1){//À vista
      $hasCheque = false;
      setQtdParcelas(1);
      $("#div-valor-recebido").show();
      $("#div-valor-troco").show();
    }else if($(this).val()==2){//Cartao
      $hasCheque = false;
      setQtdParcelas(12);
    }else if($(this).val()==3){//Cheque
      $hasCheque = true;
      setQtdParcelas(1);
    }else if($(this).val()==5){//Cheque parcelado
      $hasCheque = true;
      setQtdParcelas(12);
    }else if($(this).val()==4){//Parcelado
      $hasCheque = false;
      setQtdParcelas(12);
    }
  }).change();

  $("#dia_vencimento").keyup(function(){
    var x = $(this).val();
    if (x >= 1 && x <= 31) {
      var parcelas = $("#parcela").val();
      for(var i=0; i<parcelas; i++){
        $day = $("#dia_vencimento").val() == "" ? "1" :  $("#dia_vencimento").val();
        $("#datavencimento"+i).data("DateTimePicker").date(moment(new Date()).add(i+1, 'M').set('date', x).format('DD/MM/YYYY'));
      }
    }else $(this).val('');
  });

  $("body").on('click','.upload-cheque',function(){
    var id=$(this).parent().siblings().first().find('h4').text().substr(0,1);
    $("#modal-title").text('Cheque - Parcela '+$(this).parent().siblings().first().find('h4').text()+' - Valor: R$ '+$("#parcelasvalor"+(id-1)).val());
    var content = '<div class="col-sm-12"><div class="form-group col-sm-4">'+
    '<label>Pessoa</label>'+
    '<select class="form-control" id="tipopessoa">'+
    '<option value="f">Física</option>'+
    '<option value="j">Jurídica</option>'+
    '</select>'+
    '</div>'+
    '<div class="form-group col-sm-4">'+
    '<label>Histórico do Cheque</label>'+
    '<input class="form-control" type="text" id="historicocheque"/>'+
    '</div>'+
    '<div class="form-group col-sm-4">'+
    '<label>Data de emissão</label>'+
    '<div class="form-group">'+
    '<div class="input-group datepickervecimento" id="">'+
    '<span class="input-group-addon btn">'+
    '<span class="glyphicon glyphicon-calendar"></span>'+
    '</span>'+
    '<input id="dataemissao" type="text" class="form-control"/>'+
    '</div>'+
    '</div>'+
    '</div></div>'+
    '<div class="col-sm-12"><div class="form-group col-sm-6">'+
    '<label>Nome do Emitente</label>'+
    '<input class="form-control" type="text" id="nomeeminente"/>'+
    '</div>'+
    '<div class="form-group col-sm-6">'+
    '<label class="col-sm-12">Banco</label>'+
    '<select style="width: 100%" class="form-control js-example-basic-single banks" id="banco_id">'+
    '</select>'+
    '</div></div>'+
    '<div class="col-sm-12"><div class="form-group col-sm-4">'+
    '<label>Agência</label>'+
    '<input class="form-control" type="text" id="agencia"/>'+
    '</div>'+
    '<div class="form-group col-sm-4">'+
    '<label>Conta corrente</label>'+
    '<input class="form-control" type="text" id="contacorrente"/>'+
    '</div>'+
    '<div class="form-group col-sm-4">'+
    '<label>Número do Cheque</label>'+
    '<input class="form-control" type="text" id="numerocheque"/>'+
    '</div></div>'+
    '<div class="col-sm-12"><div class="form-group col-sm-4">'+
    '<label>Valor</label>'+
    '<div class="input-group">'+
    '<span class="input-group-addon">R$</span>'+
    '<input id="valor-cheque" class="form-control dinheiro" type="text"/>'+
    '</div>'+
    '</div>'+
    '<div class="form-group col-sm-4">'+
    '<label>Vencimento</label>'+
    '<div class="form-group">'+
    '<div class="input-group datepickervecimento">'+
    '<span class="input-group-addon btn">'+
    '<span class="glyphicon glyphicon-calendar"></span>'+
    '</span>'+
    '<input type="text" class="form-control" id="datavencimento"/>'+
    '</div>'+
    '</div>'+
    '</div>'+
    '<div class="form-group col-sm-4">'+
    '<label>CPF/CPNJ do Cheque</label>'+
    '<input class="form-control" type="text" id="cpfcnpj"/>'+
    '</div></div>'+
    '<div class="col-sm-12"><div class="form-group col-sm-3">'+
    '<label>Digitalização</label>'+
    '<button type="button" class="btn btn-warning upload-cheque-btn"><span class="glyphicon glyphicon-upload"></span> Enviar arquivo</button>'+
    '<input id="anexocheque" type="file" class="hidden"/>'+
    '</div>'+
    '<div class="text-center col-sm-9"><img id="chequepreview" accept="image/*" style="width:30%;" src="'+$urlserver+'/img/cheque-icon.png" alt="" class="img-responsive img-thumbnail"></div>'+
    '</div>';

    $("#modal-conteudo").html(content);
    $('.dinheiro').autoNumeric("init",{
      aSep: '.',
      aDec: ','
    });
    if( $("select.banks").length > 0 ) getallbanks();
    setDatePickerVencimento();
    $(".js-example-basic-single").select2();
    $("#modalReceberVenda").modal('show');
  });

  $.fn.modal.Constructor.prototype.enforceFocus = function() {};

  function setQtdParcelas(qtd){
    var content = "";
    for(var i=0; i<qtd; i++){
      content += '<option value="'+(i+1)+'">'+(i+1)+'x</option>';
    }
    $("#parcela").html(content);
    if($("#parcela").children().length == 1) $("#parcela option :first").prop('selected', 'selected');
    else $("#parcela").val(3);
    updateParcelas($("#parcela").val());
    $cheques = Array();
  }

  var $cheques = Array();

  $("body").on('click', '.salvar-cheque', function(){
    if(getObjValueWithoutMask($("#valor-cheque")).trim().length==0 || $("#nomeeminente").val().trim().length==0)
    alert("O CHEQUE NÃO PODE SER CONCLUÍDO!\nINFORMAÇÕES IMPORTANTES ESTÃO FALTANDO,\nPOR FAVOR, VERIFICAR VALOR DO CHEQUE E\n O CAMPO REFERENTE AO NOME DO EMINENTE!");
    else{
      var textsize = $("#modal-title").text().length - 3;
      var idparcela = $('.upload-cheque').parent().attr('idparcela');

      var item = {};
      item ["id"] = idparcela;
      item ["tipopessoa"] = $("#tipopessoa").val();
      item ["historicocheque"] = $("#historicocheque").val();
      item ["anexocheque"] = $("#anexocheque")[0].files[0];
      item ["dataemissao"] = $("#dataemissao").val();
      item ["nomeeminente"] = $("#nomeeminente").val();
      item ["banco_id"] = $("#banco_id").val();
      item ["agencia"] = $("#agencia").val();
      item ["contacorrente"] = $("#contacorrente").val();
      item ["numerocheque"] = $("#numerocheque").val();
      item ["valor"] = getObjValueWithoutMask($("#valor-cheque"));
      item ["datavencimento"] = $("#datavencimento").val();
      item ["cpfcnpj"] = $("#cpfcnpj").val();
      item ["img"] = $("#chequepreview").clone();
      var exists = -1;
      $.each($cheques, function(i){
        if($cheques[i].id == idparcela){
          exists = i;
          return;
        }
      });

      if(exists != -1){
        $cheques[exists] = item;
      }else{
        $cheques.push(item);
      }
      updateParcelasCheque();
      $("#modalReceberVenda").modal('hide');

      console.log($cheques);
    }
  });

  function updateParcelasCheque(){
    var collection = $(".cheque-opcoes");
    collection.each(function() {
      var div = $(this);
      var idparcela = $(this).attr('idparcela');
      $.each($cheques, function(i){
        if($cheques[i].id == idparcela){
          div.html('<label class="col-sm-12">Cheque </label> '+
          '<button type="button" class="btn btn-warning upload-cheque-edit" idparcela="'+idparcela+'"><span class="glyphicon glyphicon-pencil"></span></button>'+
          ' <button type="button" class="btn btn-danger upload-cheque-clear" idparcela="'+idparcela+'"><span class="glyphicon glyphicon-remove"></span></button>');
          return;
        }
      });
    });
  }

  $("body").on('click','.upload-cheque-clear', function(){
    //clear Cheque in array
    var idparcela = $(this).attr('idparcela');
    $.each($cheques, function(i){
      if($cheques[i].id == idparcela){
        $cheques.splice(i,1);
        return;
      }
    });
    $(this).parent().html('<label class="col-sm-12">Cheque </label> '+
    '<button type="button" class="btn btn-success upload-cheque"><span class="glyphicon glyphicon-upload"></span> Enviar arquivo</button>');
  });

  $("body").on('click','.upload-cheque-edit',function(){
    var idparcela = $(this).attr('idparcela');
    var title = $("#modal-title").text();
    var s1 = title.substr(0,title.length-3), s2=title.substr(title.length-2,2);
    var newtitle = s1+idparcela+s2;
    console.log(idparcela);
    $("#modal-title").text(newtitle);
    $.each($cheques, function(i){
      if($cheques[i].id == idparcela){
        $("#tipopessoa").val($cheques[i].tipopessoa);
        $("#historicocheque").val($cheques[i].historicocheque);
        $("#dataemissao").val($cheques[i].dataemissao);
        $("#nomeeminente").val($cheques[i].nomeeminente);
        $("#banco_id").val($cheques[i].banco_id);
        $("#agencia").val($cheques[i].agencia);
        $("#contacorrente").val($cheques[i].contacorrente);
        $("#numerocheque").val($cheques[i].numerocheque);
        setObjValMoneyMask($("#valor-cheque"),parseFloat($cheques[i].valor));
        $("#datavencimento").val($cheques[i].datavencimento);
        $("#cpfcnpj").val($cheques[i].cpfcnpj);
        $("#chequepreview").replaceWith($cheques[i].img);
        return;
      }
    });
    $("#modalReceberVenda").modal('show');
  });
  $("body").on('click','.upload-cheque-btn',function(){
    $('#anexocheque').click();
  });
  $("body").on('change','#anexocheque',function(){
    showMyImage($(this)[0].files);
  }).change();

  function showMyImage(fileInput) {
    var files = fileInput;
    for (var i = 0; i < files.length; i++) {
      var file = files[i];
      var imageType = /image.*/;
      if (!file.type.match(imageType)) {
        continue;
      }
      var img=document.getElementById("chequepreview");
      img.file = file;
      var reader = new FileReader();
      reader.onload = (function(aImg) {
        return function(e) {
          aImg.src = e.target.result;
        };
      })(img);
      reader.readAsDataURL(file);
    }
  }

  $("#pagarvendaconfirm").click(function(){
    $("#modal-title2").text('Confirmar Detalhes do Recebimento');
    var content = '<div class="col-sm-12">Valor total da venda: '+$("#valor_total_liquido").val()+
    '</div><div class="col-sm-12">'+
    '<div class="form-group col-sm-3">'+
    '<div class="checkbox">'+
    '<label>'+
    '<input name="com_nota" type="checkbox"> Com nota'+
    '</label>'+
    '</div>'+
    '</div>';
    $("#modal-conteudo2").html(content);
    var obj = $cheques.reduce(function(o, v, i) {
      o[i] = v;
      return o;
    }, {});
    $("#valor_recebido_hidden").val(getObjValueWithoutMask($("#valor_recebido")));
    $("#valor_troco_hidden").val(getObjValueWithoutMask($("#valor_troco")));
    $("#todasasparcelas").val(JSON.stringify(obj));
    $("#valor_total_venda_hidden").val(getObjValueWithoutMask($("#valor_total_liquido")));
    $("#modalReceberVendaConfirmar").modal('show');
  });

  $("#pagarcontaconfirm").click(function(){
    $("#modal-title2").text('Confirmar Detalhes do Pagamento');
    var $parcelas = Array();
    var qtd_de_meses = 1;
    var v = {};
    if($("#recorrencia").is(':checked')){
      qtd_de_meses = $("#qtd_meses").val();
      if(!$.isNumeric( qtd_de_meses )) {
        alert("Quantidade de meses não válida!");
        return;
      }
      var data_v = $("#datavencimentopm").data("DateTimePicker").date().format('YYYY-MM-DD');
      v['numero'] = 1;
      v['data_vencimento'] = data_v;
      v['valor'] = getObjValueWithoutMask($("#valor_total_liquido"));
      $parcelas.push(v);
      for(var i=1; i<qtd_de_meses; i++){
        v={};
        v['numero'] = i+1;
        data_v = $("#datavencimentopm").data("DateTimePicker").date().add((i), 'months').format('YYYY-MM-DD');
        v['data_vencimento'] = data_v;
        v['valor'] = getObjValueWithoutMask($("#valor_total_liquido"));
        $parcelas.push(v);
      }
    }else{
      v = {};
      v['numero'] = 1;
      v['data_vencimento'] = $("#datavencimentoin").data("DateTimePicker").date().format('YYYY-MM-DD');
      v['valor'] = getObjValueWithoutMask($("#valor_total_liquido"));
      $parcelas.push(v);
    }
    var content = '<p>Valor total da conta: '+$("#valor_total_liquido").val()+
    '</p>';
    $("#modal-conteudo2").html(content);
    var obj = $parcelas.reduce(function(o, v, i) {
      o[i] = v;
      return o;
    }, {});
    console.log(obj);
    $("#quantidade_parcelas").val(qtd_de_meses);
    $("#todasasparcelas").val(JSON.stringify(obj));
    var valor_total = (qtd_de_meses * getObjValueWithoutMask($("#valor_total_liquido")));
    $("#valor_total_hidden").val(valor_total);
    $("#modalPagarContaConfirmar").modal('show');
  });

  $("#pagarvenda").click(function(){
    $(this).html('Carregando...');
    $(this).attr("disabled","disabled");
    var $chequesfiles = {};
    $.each($cheques, function(i){
      $chequesfiles[i]=$cheques[i].anexocheque;
    });
    $("#valor_desconto_hidden").val(getObjValueWithoutMask($("#valor_desconto")));
    $("#valor_total_liquido_hidden").val(getObjValueWithoutMask($("#valor_total_liquido")));
    var fdata = new FormData($('#formulario')[0]);
    $.each($("#parcelas-content .parcela-row"), function(i){
      if(!($cheques === undefined) && $cheques.length > 0 && $cheques[i]!=undefined) fdata.append('cheques[]', $cheques[i].anexocheque);
      fdata.append('parcelasvencimento[]', $("#datavencimentoinput"+i).val());
      fdata.append('parcelasvalor[]', getObjValueWithoutMask($("#parcelasvalor"+i)));
      // console.log( $("#parcelasvalor"+i).val());
      fdata.append('parcelasobs[]', $("#parcelasobs"+i).val());
    });
    var route = $("form").attr("action");
    $.ajax({
      url:route,
      type: 'POST',
      data: fdata,
      dataType: 'json',
      contentType: false,
      processData: false,
      success: function(data){
        if(data.data.success){
          $('#modalReceberVendaConfirmar').modal('hide');
          sendtoprinter($urlserver+"/printcupom/"+$("venda_id").val());
          alert('Informações salvas com sucesso!');
          window.location.replace('/vendas');
        }else{
          alert(data.data.msg);
        }
      },
      error: function(data){
        console.log(data);
      }
    });
  });

  $("#pagarconta").click(function(){
    var $chequesfiles = {};
    $.each($cheques, function(i){
      $chequesfiles[i]=$cheques[i].anexocheque;
    });
    var fdata = new FormData($('#formulario')[0]);
    $.each($("#parcelas-content .parcela-row"), function(i){
      if(!($cheques === undefined) && $cheques.length > 0 && $cheques[i]!=undefined) fdata.append('cheques[]', $cheques[i].anexocheque);
      fdata.append('parcelasvencimento[]', $("#datavencimentoinput"+i).val());
      fdata.append('parcelasvalor[]', $("#parcelasvalor"+i).val());
      // console.log( $("#parcelasvalor"+i).val());
      fdata.append('parcelasobs[]', $("#parcelasobs"+i).val());
    });
    var route = $("form").attr("action");
    $.ajax({
      url:route,
      type: 'POST',
      data: fdata,
      dataType: 'json',
      contentType: false,
      processData: false,
      success: function(data){
        if(data.data.success){
          $('#modalPagarContaConfirmar').modal('hide');
          alert(data.data.msg);
          window.location.replace('/financeiro/contasareceber');
        }else{
          alert(data.data.msg);
        }
      },
      error: function(data){
        console.log(data);
      }
    });
  });

  $("#salvarconta").click(function(){
    var fdata = new FormData($('#formulario')[0]);
    var route = $("form").attr("action");
    $.ajax({
      url:route,
      type: 'POST',
      data: fdata,
      dataType: 'json',
      contentType: false,
      processData: false,
      success: function(data){
        if(data.data.success){
          $('#modalPagarContaConfirmar').modal('hide');
          alert(data.data.msg);
          window.location.replace('/financeiro/contasapagar');
        }else{
          alert(data.data.msg);
        }
      },
      error: function(data){
        console.log(data);
      }
    });
  });

  function calcular_troco(){
    $("#valor_recebido").keyup(function(){
      var v_recebido = getObjValueWithoutMask($(this));
      var v_total = getObjValueWithoutMask($("#valor_total_liquido"));
      var v_troco = (v_recebido - v_total);
      setObjValMoneyMask($("#valor_troco"),v_troco);
    });
  }
  calcular_troco();
  $("body").on('click', '.view-recebimento', function(){
    var modal_inner_content = "";
    var route = $urlserver+"/"+$(this).attr("url");
    var current_id = $(this).attr("current_id");
    $.ajax({
      url:route,
      type: 'get',
      success: function(data){
        data = JSON.parse(data);
        $.each(data, function(i){
          var valor_parcela = Number(data[i].valor).toLocaleString("pt-BR", {style: "currency", currency: "BRL", minimumFractionDigits: 2})
          modal_inner_content += (data[i].status == "pendente") ? "<tr class='warning'>" : (data[i].status == "vencida") ?  "<tr class='danger'>" : "<tr class='success'>";
          modal_inner_content += "<td class='middel-align'>"+ ((data[i].id == current_id) ? ('<span class="label label-primary">'+data[i].id+'</span>') : data[i].id )+"</td>";
          modal_inner_content += "<td>"+valor_parcela+"</td>";
          modal_inner_content += "<td>"+Number(data[i].valor_pago).toLocaleString("pt-BR", {style: "currency", currency: "BRL", minimumFractionDigits: 2})+"</td>";
          modal_inner_content += "<td>"+new Date(data[i].data_vencimento).toLocaleDateString()+"</td>";
          modal_inner_content += "<td>"+ ((data[i].status == 'pago') ? (new Date(data[i].data_pago).toLocaleDateString()) : '-') +"</td>";
          modal_inner_content += "<td>"+data[i].status+"</td>";
          modal_inner_content += '<td><button class="btn btn-success pull-left'+ ((data[i].status != "pago") ? ' view-recebimento-cash"' : '"disabled') +' url="parceladoreceber/'+data[i].id+'" valor_parcela="'+data[i].valor+'" data-toggle="tooltip" data-placement="top" title="Receber"><span class="glyphicon glyphicon-usd" aria-hidden="true"></span></button></td>';
          modal_inner_content += "</tr>";
        });
        $("#modal-conteudo > table > tbody").html(modal_inner_content);
        $("#modalViewRecebimento").modal('show');
      },
      error: function(data){
        console.log(data);
      }
    });
  });

  $("body").on('click', '.view-recebimento-cash', function(){
    $("#modalViewRecebimento").modal('hide');
    var url = $(this).attr('url');
    var valor_parcela = $(this).attr("valor_parcela");
    var modal_inner_content = '<div class="row"><div class="form-group col-sm-4">'+
    '<div class="input-group col-sm-12">'+
    '<label>Valor parcela</label>'+
    '<div class="input-group col-sm-12">'+
    '<span class="input-group-addon">R$ </span>'+
    '<input id="valor_total_liquido" class="form-control dinheiro" disabled type="text"/>'+
    '<input id="valor_total_hidden" type="hidden" name="valor_total"/>'+
    '<input id="url" type="hidden" value="'+url+'"/>'+
    '</div>'+
    '</div>'+
    '</div>'+
    '<div class="form-group col-sm-4" id="div-valor-recebido">'+
    '<label>Valor recebido</label>'+
    '<div class="input-group col-sm-12">'+
    '<span class="input-group-addon">R$ </span>'+
    '<input id="valor_recebido" class="form-control dinheiro" type="text"/>'+
    '<input id="valor_recebido_hidden" type="hidden" name="valor_recebido"/>'+
    '</div>'+
    '</div>'+
    '<div class="form-group col-sm-4" id="div-valor-troco">'+
    '<label>Troco</label>'+
    '<div class="input-group col-sm-12">'+
    '<span class="input-group-addon">R$ </span>'+
    '<input id="valor_troco" class="form-control dinheiro" type="text" disabled value=""/>'+
    '<input id="valor_troco_hidden" type="hidden" name="valor_troco"/>'+
    '</div>'+
    '</div></div>';
    var route = $(this).attr("url");
    $("#modal-conteudo2").html(modal_inner_content);
    $("#modalViewRecebimentoCash").modal('show');
    $(".dinheiro").autoNumeric("init",{
      aSep: '.',
      aDec: ','
    });
    setObjValMoneyMask($("#valor_total_liquido"),valor_parcela);
    calcular_troco();

  });
  $("body").on('click', '.view-pagamento-cash', function(){
    $("#modalViewRecebimento").modal('hide');
    var url = $(this).attr('url');
    var valor_parcela = $(this).attr("valor_parcela");
    var modal_inner_content = '<div class="row"><div class="form-group col-sm-6">'+
    '<div class="input-group col-sm-12">'+
    '<label>Valor parcela</label>'+
    '<div class="input-group col-sm-12">'+
    '<span class="input-group-addon">R$ </span>'+
    '<input id="valor_total_liquido" class="form-control dinheiro" disabled type="text"/>'+
    '<input id="valor_total_hidden" type="hidden" name="valor_total"/>'+
    '<input id="url" type="hidden" value="'+url+'"/>'+
    '</div>'+
    '</div>'+
    '</div>'+
    '<div class="form-group col-sm-6" id="div-valor-recebido">'+
    '<label>Valor pago</label>'+
    '<div class="input-group col-sm-12">'+
    '<span class="input-group-addon">R$ </span>'+
    '<input id="valor_recebido" class="form-control dinheiro" type="text"/>'+
    '<input id="valor_recebido_hidden" type="hidden" name="valor_pago"/>'+
    '</div>'+
    '</div>'+
    '<div class="form-group col-sm-6" id="div-valor-desconto">'+
    '<label>Desconto</label>'+
    '<div class="input-group col-sm-12">'+
    '<span class="input-group-addon">R$ </span>'+
    '<input id="valor_desconto" class="form-control dinheiro" type="text" value="0.0"/>'+
    '<input id="valor_desconto_hidden" type="hidden" name="valor_desconto"/>'+
    '</div>'+
    '</div>'+
    '<div class="form-group col-sm-6" id="div-valor-juros">'+
    '<label>Juros</label>'+
    '<div class="input-group col-sm-12">'+
    '<span class="input-group-addon">R$ </span>'+
    '<input id="valor_troco" class="form-control dinheiro" type="text" value="0.0"/>'+
    '<input id="valor_troco_hidden" type="hidden" name="valor_juros"/>'+
    '</div>'+
    '</div></div>';
    var route = $(this).attr("url");
    $("#modal-conteudo2").html(modal_inner_content);
    $("#modalViewRecebimentoCash").modal('show');
    $(".dinheiro").autoNumeric("init",{
      aSep: '.',
      aDec: ','
    });
    setObjValMoneyMask($("#valor_total_liquido"),valor_parcela);
    calcular_troco();
    $("#modalViewRecebimentoCash .modal-title").html('Detalhes do Pagamento');
  });
  $('#modalViewRecebimentoCash').on('shown.bs.modal', function () {
    $("#valor_recebido").focus();
  });
  $("#edit-sending-cash").on('click',function(){
    var new_route = $('#form').prop('action').replace('financeiroreceber/XXX', $("#url").val());
    // alert(new_route);
    $("#valor_troco_hidden").val(getObjValueWithoutMask($("#valor_troco")));
    $("#valor_recebido_hidden").val(getObjValueWithoutMask($("#valor_recebido")));
    $("#valor_total_hidden").val(getObjValueWithoutMask($("#valor_total_liquido")));
    $('#form').prop('action',new_route);
    $('#form').submit();
  });

  $("body").on('click','.pagarvendabt',function(){
    window.location.replace($(this).attr('url'));
  });
  $("body").on('click','.edit-pagamento-conta',function(){
    window.location.replace($(this).attr('url'));
  });
  $("body").on('click', '.view-pagamento', function(){
    var modal_inner_content = "";
    var route = $urlserver+"/"+$(this).attr("url");
    var current_id = $(this).attr("current_id");
    $.ajax({
      url:route,
      type: 'get',
      success: function(data){
        data = JSON.parse(data);
        $.each(data, function(i){
          var valor_parcela = Number(data[i].valor).toLocaleString("pt-BR", {style: "currency", currency: "BRL", minimumFractionDigits: 2})
          modal_inner_content += (data[i].status == "pendente") ? "<tr class='warning'>" : (data[i].status == "vencida") ?  "<tr class='danger'>" : "<tr class='success'>";
          modal_inner_content += "<td class='middel-align'>"+ ((data[i].id == current_id) ? ('<span class="label label-primary">'+data[i].id+'</span>') : data[i].id )+"</td>";
          modal_inner_content += "<td>"+valor_parcela+"</td>";
          modal_inner_content += "<td>"+Number(data[i].valor_pago).toLocaleString("pt-BR", {style: "currency", currency: "BRL", minimumFractionDigits: 2})+"</td>";
          modal_inner_content += "<td>"+new Date(data[i].data_vencimento).toLocaleDateString()+"</td>";
          modal_inner_content += "<td>"+ ((data[i].status == 'pago') ? (new Date(data[i].data_pago).toLocaleDateString()) : '-') +"</td>";
          modal_inner_content += "<td>"+data[i].status+"</td>";
          modal_inner_content += '<td><button class="btn btn-success pull-left'+ ((data[i].status != "pago") ? ' view-pagamento-cash"' : '"disabled') +' url="parceladoreceber/'+data[i].id+'" valor_parcela="'+data[i].valor+'" data-toggle="tooltip" data-placement="top" title="Receber"><span class="glyphicon glyphicon-usd" aria-hidden="true"></span></button></td>';
          modal_inner_content += "</tr>";
        });
        $("#modal-conteudo > table > tbody").html(modal_inner_content);
        $("#modalViewRecebimento").modal('show');
      },
      error: function(data){
        console.log(data);
      }
    });
  });

  $('input#recorrencia').change(function () {
    if ($(this).is(':checked')) {
      $("#datavencimento").hide();
      showRecorrenciaOptions();
    } else {
      $("#content").html('');
      $("#datavencimento").show();
    }
  });

  function showRecorrenciaOptions(){
    var content = '<div class="form-group col-sm-3">'+
    '<label>1° Mês </label>'+
    '<div class="form-group">'+
    '<div class="input-group datepickervecimento" id="datavencimentopm">'+
    '<input class="form-control" id="datavencimentoprimeiromes" type="text">'+
    '<span class="input-group-addon btn">'+
    '<span class="glyphicon glyphicon-calendar"></span></span>'+
    '</div>'+
    '</div>'+
    '</div>'+
    '<div class="form-group col-sm-3">'+
    '<label>Qtd. Meses </label>'+
    '<div class="form-group">'+
    '<input class="form-control" id="qtd_meses" type="text"/>'
    '</div>'+
    '</div>';
    $("#content").html(content);
    setDatePickerVencimento();
  }
  setDatePickerVencimento();
  if( $("select.banks").length>0 ) getallbanks();
  $("body").on('click','.view-cheque',function(){
    $.ajax({
      url: $urlserver+"/"+$(this).attr('url'),
      type: "get",
      success: function(data){
        data = JSON.parse(data);
        $("#nomeeminente").val(data.nome);
        $("#chequepreview").prop('src',data.caminho);
        $("#datavencimento").val(moment(data.data_vencimento).format('DD/MM/YYYY'));
        $("#dataemissao").val(moment(data.data_emissao).format('DD/MM/YYYY'));
        $("#historico").val(data.historico);
        $("#agencia").val(data.agencia);
        $("#numerocheque").val(data.numero_cheque);
        $("#contacorrente").val(data.conta_corrente);
        $("#cpfcnpj").val(data.cpfcnpj);
        $("#valor-cheque").val(Number(data.valor).toLocaleString("pt-BR", {style: "currency", currency: "BRL", minimumFractionDigits: 2}));
        $("#banco_id").val(data.banco_id).change();
        $("#tipopessoa").val(data.tipo_pessoa);
        $("#modalReceberVenda").modal('show');
      },
      error: function(data){
        console.log(data);
      }
    });
  });

  $("body").on('click','.view-historico',function(){
    $.ajax({
      url: $urlserver+"/"+$(this).attr('url'),
      type: "get",
      success: function(data){
        data = JSON.parse(data);
        $("#historico-edit").val(data.historico);
        $("#historico-not-edit").val(data.historico);
        $("#update-cheque").attr('url',$(this).attr('url'));
        $("#modalHistoricoCheque").modal('show');
      },
      error: function(data){
        console.log(data);
      }
    });
  });

  $("body").on("click", "button.update-cheque", function(d){
    var url = $(this).attr('url');
    console.log(url);
    $('#form').prop('action',url);
    $('#form').submit();
  });

  function getAllCategorias(){
    if($("#categorias").length > 0){

      $.ajax({
        url: $urlserver+"/categorias/all",
        type: "get",
        success: function(data){
          data = JSON.parse(data);
          var $str = "", $str2 = "";
          var categoria_mae = "";
          if(data.length == 0){
            $str = "Nenhuma categoria cadastrada";
          } else{
            $.each(data, function(key){
              // if(categoria_mae != data[key].categoria.nome){
              //   if(categoria_mae != ""){
              //     $str +='</div></div></div>';
              //   }
              $str += '<div class="panel panel-default">'+
              '<div class="panel-heading" role="tab" id="headingOne'+key+'">'+
              '<h4 class="panel-title">'+
              '<input id="categoria_check_'+data[key].id+'" name="categorias[]" type="checkbox" value="'+data[key].id+'"> '+
              '<a role="button" data-toggle="collapse" data-parent="" href="#collapseOne'+key+'" aria-expanded="true" aria-controls="collapseOne">'+
              data[key].nome+
              '</a>'+
              '</h4>'+
              '</div>'+
              '<div id="collapseOne'+key+'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">'+
              '<div id="panel-body'+data[key].id+'" class="panel-body">';
              var categoria_vez = data[key].id;
              $.ajax({
                url: $urlserver+"/subcategorias/all",
                type: "get",
                success: function(subcat){
                  subcat = $.parseJSON(subcat);
                  var $str2 = "";
                  $.each(subcat, function(j, c){
                    if(subcat[j].categoria_id == categoria_vez){
                      $str2 += '<div class="form-group col-sm-3">'+
                      '<div class="checkbox"><label>'+
                      '<input id="subcategoria_check_'+c.id+'" name="subcategorias[]" type="checkbox" value="'+c.id+'">'+c.nome+
                      '</label></div></div>';
                    }
                  });
                  $("#panel-body"+categoria_vez).html($str2);
                },
                error: function(){console.log('erro ao tentar pegar subcategorias');}
              });
              $str +='</div></div></div>';
              // }
            });
          }
          $("#categorias").html($str);
          $('#categorias').collapse()
          // if($("#unidade_salva").length > 0) $("select#unidades").val($("#unidade_salva").val());
          if($("#subcategorias_id").length > 0){
            var list = [];
            list = $("#subcategorias_id").val().replace(/ /g,'').split(',');
            $.each(list, function(i){
              $("#subcategoria_check_" + list[i]).prop('checked','checked');
            });
          }
          if($("#categorias_id").length > 0){
            var list = [];
            list = $("#categorias_id").val().replace(/ /g,'').split(',');
            $.each(list, function(i){
              $("#categoria_check_" + list[i]).prop('checked','checked');
            });
          }
        },
        error: function(data){
          console.log(data.message);
        }
      });

      //   $.ajax({
      //      url: $urlserver+"/subcategorias/all",
      //      type: "get",
      //      success: function(data){
      //          data = JSON.parse(data);
      //           var $str = "", $str2 = "";
      //           var categoria_mae = "";
      //          if(data.length == 0){
      //            $str = "Nenhuma categoria cadastrada";
      //          } else{
      //            $.each(data, function(key){
      //             if(categoria_mae != data[key].categoria.nome){
      //               if(categoria_mae != ""){
      //                 $str +='</div></div></div>';
      //               }
      //               $str += '<div class="panel panel-default">'+
      //                 '<div class="panel-heading" role="tab" id="headingOne'+key+'">'+
      //                   '<h4 class="panel-title">'+
      //                   '<input id="categoria_check_'+data[key].categoria.id+'" name="categorias[]" type="checkbox" value="'+data[key].categoria.id+'"> '+
      //                     '<a role="button" data-toggle="collapse" data-parent="" href="#collapseOne'+key+'" aria-expanded="true" aria-controls="collapseOne">'+
      //                       data[key].categoria.nome+
      //                     '</a>'+
      //                   '</h4>'+
      //                 '</div>'+
      //                 '<div id="collapseOne'+key+'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">'+
      //                   '<div class="panel-body">';
      //               categoria_mae = data[key].categoria.nome;
      //             }if(categoria_mae == data[key].categoria.nome){
      //               $str += '<div class="form-group col-sm-3">'+
      //                 '<div class="checkbox"><label>'+
      //                   '<input id="subcategoria_check_'+data[key].id+'" name="subcategorias[]" type="checkbox" value="'+data[key].id+'">'+data[key].nome+
      //                 '</label></div></div>';
      //               // $str += '<input name="subcategorias[]" type="check" class="checkbox"> <label>'+data[key].nome+'</label>';
      //             }
      //            });
      //          }
      //           $("#categorias").html($str);
      //           $('#categorias').collapse()
      //           // if($("#unidade_salva").length > 0) $("select#unidades").val($("#unidade_salva").val());
      //           if($("#subcategorias_id").length > 0){
      //             var list = [];
      //             list = $("#subcategorias_id").val().replace(/ /g,'').split(',');
      //             $.each(list, function(i){
      //               $("#subcategoria_check_" + list[i]).prop('checked','checked');
      //             });
      //           }
      //           if($("#categorias_id").length > 0){
      //             var list = [];
      //             list = $("#categorias_id").val().replace(/ /g,'').split(',');
      //             $.each(list, function(i){
      //               $("#categoria_check_" + list[i]).prop('checked','checked');
      //             });
      //           }
      //      },
      //      error: function(data){
      //        console.log(data.message);
      //      }
      //  });
    }
  }
  getAllCategorias();

  $("#add_produto_row_nota").click(function(){
    var i = $("#accordion").children().length;
    var inner_html = '<div class="panel panel-default">'+
    '<div class="panel-heading" role="tab" id="headingOne'+i+'">'+
    '<h4 class="panel-title">'+
    '<a role="button" data-toggle="collapse" data-parent="" href="#collapseOne'+i+'" aria-expanded="true" aria-controls="collapseOne">'+
    'Dados do Pruduto #'+(i+1)+
    '</a>'+
    // '<a class="pull-right remove-produtos-nota" href=""><span class="glyphicon glyphicon-trash"></span></a>'+
    '</h4>'+
    '</div>'+
    '<div id="collapseOne'+i+'" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">'+
    '<div class="panel-body">'+
    '<div class="col-sm-12">'+
    '<div class="form-group col-sm-6 col-md-12">'+
    '<label>Produto existente</label>'+
    '<select linha="'+(i+1)+'" id="produtos'+(i+1)+'" name="produto_id[]" class="form-control js-example-basic-single produto-nota"></select>'+
    '</div>'+
    '<div class="form-group col-sm-4">'+
    '<label>Código do Produto</label>'+
    '<div class="input-group">'+
    '<span class="input-group-addon"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span></span>'+
    '<input id="codigo'+(i+1)+'" class="form-control" type="type" name="codigo[]" placeholder="Informe um codigo para o produto">'+
    '</div>'+
    '</div>'+
    '<div class="form-group col-sm-4">'+
    '<label>Codigo NCM</label>'+
    '<div class="input-group">'+
    '<span class="input-group-addon"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span></span>'+
    '<input id="codigo_ncm'+(i+1)+'" class="form-control" type="type" name="codigo_ncm[]" placeholder="Informe um codigo ncm para o produto">'+
    '</div>'+
    '</div>'+
    '<div class="form-group col-sm-4">'+
    '<label>Título</label>'+
    '<div class="input-group">'+
    '<span class="input-group-addon"><span class="glyphicon glyphicon-font" aria-hidden="true"></span></span>'+
    '<input id="titulo'+(i+1)+'" class="form-control" type="type" name="titulo[]" placeholder="Informe um título para o produto">'+
    '</div>'+
    '</div>'+
    '</div>'+
    '<div class="col-sm-12">'+
    '<div class="form-group col-sm-4">'+
    '<label>Unidade de Medida</label>'+
    '<div class="input-group">'+
    '<span class="input-group-addon"><span class="glyphicon glyphicon-glass" aria-hidden="true"></span></span>'+
    '<select class="form-control" id="unidades'+(i+1)+'" name="unidade_id[]"></select>'+
    '</div>'+
    '<a href="../unidades/create"> Unidade de medida ainda não cadastrada?</a>'+
    '</div>'+
    '<div class="form-group col-sm-4">'+
    '<label>Quantidade</label>'+
    '<div class="input-group">'+
    '<span class="input-group-addon"><span class="glyphicon glyphicon-tag" aria-hidden="true"></span></span>'+
    '<input id="quantidade'+(i+1)+'" name="quantidade_estoque[]" type="number" class="form-control" aria-label="Quantidade em estoque">'+
    '</div>'+
    '</div>'+
    '<div class="form-group col-sm-4">'+
    '<label>Custo</label>'+
    '<div class="input-group">'+
    '<span class="input-group-addon">R$</span>'+
    '<input id="custo'+(i+1)+'" type="text" name="custo[]" class="form-control dinheiro" aria-label="Custo do produto">'+
    '<input id="custo_produto'+(i+1)+'" type="hidden" name="custo_produto[]">'+
    '</div>'+
    '</div>'+
    '</div>'+
    '<div class="col-sm-12">'+
    '<div class="form-group col-sm-4">'+
    '<label>Impostos</label>'+
    '<div class="input-group">'+
    '<span class="input-group-addon"><span class="glyphicon glyphicon-piggy-bank" aria-hidden="true"></span></span>'+
    '<select id="impostos'+(i+1)+'" class="form-control" id="impostos"></select>'+
    '<span id="addimposto" linha="'+(i+1)+'" class="btn input-group-addon btn-primary addimposto-nota"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></span>'+
    '</div>'+
    '<a href="../impostos/create"> Imposto ainda não cadastrado?</a>'+
    '<input id="impostos_id'+(i+1)+'" name="impostos_id[]" type="text" class="impostos_id'+(i+1)+' hidden">'+
    '<div id="imposto-lista'+(i+1)+'" class="form-group"><div class="selecionarimpostos" id="selecionarimpostos'+(i+1)+'"></div></div>'+
    '</div>'+
    '<div class="form-group col-sm-4">'+
    '<label>Frete</label>'+
    '<div class="input-group">'+
    '<span class="input-group-addon">R$</span>'+
    '<input id="frete'+(i+1)+'" type="text" name="frete[]" class="form-control dinheiro" aria-label="Frete do produto">'+
    '<input id="frete_produto'+(i+1)+'" type="hidden" name="frete_produto[]">'+
    '</div>'+
    '</div>'+
    '<div class="form-group col-sm-4">'+
    '<label>Preço do Produto</label>'+
    '<div class="input-group">'+
    '<span class="input-group-addon">R$</span>'+
    '<input id="preco'+(i+1)+'" type="text" name="preco[]" class="form-control dinheiro" aria-label="Valor do produto">'+
    '<input id="preco_produto'+(i+1)+'" name="preco_produto[]" type="hidden">'+
    '</div>'+
    '</div>'+
    '<div class="form-group col-sm-12">'+
    '<label>Descrição</label>'+
    '<textarea id="descricao'+(i+1)+'" name="descricao[]" class="form-control" placeholder="Informe alguma descrição para o produto"></textarea>'+
    '</div>'+
    '</div>';
    $("#accordion").append(inner_html);

    // evento_calcular_qtd();
    // evento_deletar();
    $('.dinheiro').autoNumeric("init",{
      aSep: '.',
      aDec: ','
    });
    getallprodutostoselect(i+1);
    getAllImpostosToSelect(i+1);
    getallunidadestoselect(i+1);
    add_imposto_dynamic();
    evento_deletar_produto_nota();
    getAllImpostos();
    event_select_produto_nota();
  });

  function evento_deletar_produto_nota(){
    $("body").on('click', '.remove-produtos-nota', function(e){
      e.preventDefault();
      if(confirm('Desejas realmente remover esse produto?')){
        var linha_tabela = $( this ).parent().parent().parent().first();
        linha_tabela.fadeOut(500, function() { $(this).remove(); });
      }
    });
  }

  event_select_produto_nota();
  var list_produtos_selected = [];
  function event_select_produto_nota(){
    $(".produto-nota").change(function(){
      var produto_id = $(this).val();
      // console.log($.inArray(Number(produto_id), list_produtos_selected));
      if(($.inArray(Number(produto_id), list_produtos_selected) === 0)){
        alert('Produto já adicionado!');
        $("#produtos"+linha).val(0);
      }else{
        list_produtos_selected.push(Number(produto_id));
        var linha = $(this).attr('linha');
        $.ajax({
          url: $urlserver+"/produtos/"+produto_id,
          type: "get",
          success: function(data2){
            data2 = JSON.parse(data2).produtos;
            var data = data2[0];
            $("#titulo"+linha).val(data.titulo);
            $("#codigo"+linha).val(data.codigo);
            $("#codigo_ncm"+linha).val(data.codigo_ncm);
            setObjValMoneyMask($("#custo"+linha),data.custo);
            setObjValMoneyMask($("#preco"+linha),data.preco);
            setObjValMoneyMask($("#frete"+linha),data.frete);
            $("#descricao"+linha).val(data.descricao);
            $("#quantidade"+linha).val(data.quantidade_estoque);
            $("#unidades"+linha).val(data.unidade_id);
            var list = [];
            $.each(data.impostos, function(i){
              list.push(data.impostos[i].id);
            });
            $("#impostos_id"+linha).val(list.toString());
            showImpostosByElement(linha);
          },
          error: function(data){
            console.log(data);
          }
        });
      }
    });
  }

  function showImpostosByElement(linha){
    var list = [];
    list = $("#impostos_id"+linha).val().replace(/ /g,'').split(',');
    $.each(list, function(i){
      var theDiv = $("#impostos_id"+linha).siblings("div#imposto-lista"+linha).find(".is"+list[i]);
      if(theDiv.hasClass("hidden"))
      theDiv.fadeOut().removeClass("hidden");
    });
  }

  function add_imposto_dynamic(){
    $("body").on('click','.addimposto-nota', function(){
      var linha = $(this).attr('linha');
      var value = $(this).prev('select').find('option:selected').val();
      var theDiv = $("#imposto-lista"+linha).find(".is" + value);
      if(theDiv.hasClass("hidden"))
      theDiv.fadeOut().removeClass("hidden");
    });
  }
  add_imposto_dynamic();
  if($("select#produtos1").length > 0)getallprodutostoselect('1');
  getAllImpostosToSelect('1');
  if( $("select#unidades1").length > 0) getallunidadestoselect('1');
  function getallprodutostoselect(linha){
    $.ajax({
      url: $urlserver+"/produtos/all",
      type: "get",
      success: function(data){
        data = JSON.parse(data);
        var $str = "", $str2 = "";
        if(data.length == 0) $str = "<option value=''>Nenhum produto cadastrado</option>";
        else{
          $str = "<option value=''>Selecione um produto</option>";
          $.each(data, function(key){
            if(list_produtos_selected.length > 0){
              if($.inArray(data[key].id, list_produtos_selected) === -1){
                $str += "<option value='"+data[key].id+"'>"+data[key].titulo+"</option>";
              }
            }else{
              $str += "<option value='"+data[key].id+"'>"+data[key].titulo+"</option>";
            }
          });
        }
        $("select#produtos"+linha).html($str);
        $("select#produtos"+linha).select2();
      },
      error: function(data){
        console.log(data.message);
      }
    });
  }

  function getAllImpostosToSelect(linha){
    if($("select#impostos"+linha).length > 0){
      $.ajax({
        url: $urlserver+"/impostos/all",
        type: "get",
        success: function(data){
          data = JSON.parse(data);
          var $str = "", $str2 = "";
          if(data.length == 0) $str = "<option value='0'>Nenhum imposto cadastrado</option>";
          else{
            $str = "<option value=''>Selecione um tipo de despesa</option>";
            $.each(data, function(key){
              $str += "<option value='"+data[key].id+"'>"+data[key].nome+" ("+data[key].valor+" %)"+" </option>";
              $str2 += '<h1 style="background-color: #9e9e9e; font-size: 14px;" data-valor='+data[key].valor+' data-id="'+data[key].id+'" class="label label-default hidden is'+data[key].id+'">'+
              data[key].nome+' ('+data[key].valor+' %)'+' <a href="" class="remove" rel="'+data[key].id+'">'+
              '<span style="color: #c62828;"class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></h1> ';
            });
          }
          $("select#impostos"+linha).html($str);
          $("#selecionarimpostos"+linha).html($str2);
        },
        error: function(data){
          console.log(data);
        }
      });
    }
  }

  function getallunidadestoselect(linha){
    $.ajax({
      url: $urlserver+"/unidades/all",
      type: "get",
      success: function(data){
        data = JSON.parse(data);
        var $str = "", $str2 = "";
        if(data.length == 0) $str = "<option value='0'>Nenhuma unidade cadastrada</option>";
        else{
          $str = "<option value=''>Selecione um tipo de unidade de medida</option>";
          $.each(data, function(key){
            $str += "<option value='"+data[key].id+"'>"+data[key].nome+"</option>";
          });
        }
        $("select#unidades"+linha).html($str);
      },
      error: function(data){
        console.log(data.message);
      }
    });
  }

  $("body").on('click', '.criar-produtos-nota', function(){
    if($("#fornecedores").val() == ""){ alert('Por favor selecionar um fornecedor!');}
    else if($("#funcionarios").val() == ""){ alert('Por favor selecionar um funcionário conferente!');
  }else{
    if(confirm('Desejas realmente prosseguir?')){
      var $impostosList = Array();
      var new_value = 0;
      var imposto_node = [];
      $("[name='preco[]']").each(function(i) {
        new_value = getObjValueWithoutMask($(this));
        $("#preco_produto"+(i+1)).val(new_value);
        $("#selecionarimpostos"+(i+1)).find('h1').each(function(){
          if(!$(this).hasClass('hidden')) imposto_node.push($(this).attr('data-id'));
        });
        $impostosList.push(imposto_node.toString());
        imposto_node = [];
      });
      $("[name='custo[]']").each(function(i) {
        new_value = getObjValueWithoutMask($(this));
        $("#custo_produto"+(i+1)).val(new_value);
      });
      $("[name='frete[]']").each(function(i) {
        new_value = getObjValueWithoutMask($(this));
        $("#frete_produto"+(i+1)).val(new_value);
      });
      $("#valor_total_nota_hidden").val(getObjValueWithoutMask($("#valor_total_nota")));
      $("#valor_frete_nota_hidden").val(getObjValueWithoutMask($("#valor_frete_nota")));
      $("[name='impostos_id[]']").each(function(i) {
        $(this).val($impostosList[i]);
      });
      $("#formulario").submit();
    }
  }
});

$(".gerarpdfvendas").on('click', function(){
  $('#iframe').attr('src', $(this).attr('url')+
  "/?status="+$("#status").val()+"&vendedor_id="+
  $("#vendedor_id").val()+"&cliente_id="+
  $("#cliente_id").val()+"&data_inicio="
  +$("#data_inicio").val()+"&data_fim="
  +$("#data_fim").val()+"&com_nota="+
  +$("#com_nota").val()+"&id_venda="
  +$("#id_venda").val());

  $("#filtros").hide();
  $("#filtros_show").show();
  $('#iframe').show();
});
$(".gerarpdfrh").on('click', function(){
  $('#iframe').attr('src', $(this).attr('url')+"/?estado_id="+$("#estados").val()+"&cidade_id="+$("#cidades").val()+"&data_inicio="+$("#data_inicio").val()+"&data_fim="+$("#data_fim").val());
  $("#filtros").hide();
  $("#filtros_show").show();
  $('#iframe').show();
});
$(".gerarprodinter").on('click', function(){
  $('#iframe').attr('src', $urlserver+$(this).attr('url')+"/?qtd_estoque="+$("#qtd_estoque").val());
  $("#filtros").hide();
  $("#filtros_show").show();
  $('#iframe').show();
});
$(".gerarpdfmovimentacao").on('click', function(){
  // if($("#produto_id").val().trim().length!=0){
  $('#iframe').attr('src', $urlserver+$(this).attr('url')+"/?produto_id="+$("#produto_id").val()+"&data_ini="+$("#data_ini").val()+"&data_fim="+$("#data_fim").val());
  $("#filtros").hide();
  $("#filtros_show").show();
  $('#iframe').show();
  // }else{
  // alert('É necessário que o id de um produto seja informado!');
  // }
});
$(".gerarprodexter").on('click', function(){
  $('#iframe').attr('src', $urlserver+$(this).attr('url')+"/?qtd_estoque="+$("#qtd_estoque").val()+"&qtd_porcentagem="+$("#qtd_porcentagem").val());
  $("#filtros").hide();
  $("#filtros_show").show();
  $('#iframe').show();
});
$("#filtros_show").on('click', function(){
  $('#iframe').css('display', 'none');
  $(this).hide();
  $("#filtros").show();
});

$("#alterar_senha").change(function(){
  if($(this).is(":checked")) {
    $("#password").removeAttr('disabled');
    $("#password-confirm").removeAttr('disabled');
  }else{
    $("#password").prop('disabled', 'disabled');
    $("#password-confirm").prop('disabled', 'disabled');
  }
}).change();

$("#alterar_email").change(function(){
  if($(this).is(":checked")) {
    $("#email").removeAttr('disabled');
  }else{
    $("#email").prop('disabled', 'disabled');
  }
}).change();

if(typeof table != "undefined"){
  setInterval( function () {
    table.ajax.reload( null, false );
  }, 30000 );
}

if($("#logs_vendas").length > 0) {
  getLastVendas();
  setInterval( function () {
    getLastVendas();
  }, 30000 );
}

function getLastVendas(){
  $.ajax({
    url: $urlserver+"/vendas/all",
    type: "get",
    success: function(data){
      data = JSON.parse(data);
      var $str = "";
      if(data.length > 0){
        $str='<ul class="list-group">';
        $.each(data, function(key){
          if(key < 15)
          $str += "<li class='list-group-item'> "+
          "<span class='badge'>"+Number(data[key].valor_liquido).toLocaleString("pt-BR", {style: "currency", currency: "BRL", minimumFractionDigits: 2})+"</span>"+
          "Venda: #"+data[key].id+" / Vendedor: "+data[key].vendedor.nome
          +"</li>";
        });
        $str+="<li class='list-group-item'><a href='../vendas' class='btn btn-primary' style='display:block'>MAIS VENDAS</a></li>";
        $str+="</ul>";
      }
      $("#logs_vendas").html($str);
      // if($("#cidade_pessoa").length > 0) $("select#cidades").val($("#cidade_pessoa").val());
    },
    error: function(data){
      console.log(data);
    }
  });
}
$(document).on('keydown', null, 'f1', call_vendas);
$(document).on('keydown', null, 'f2', call_venda_nova);
$(document).on('keydown', null, 'f3', call_vendas_confirmar);
$(document).on('keydown', null, 'f4', call_cancelar_venda);
$(document).on('keydown', null, 'f5', call_no_action);
$('input').on('keydown', null, 'f5', call_no_action);
$(document).on('keydown', null, 'f6', call_produtos);
$(document).on('keydown', null, 'f7', call_consultar_produto_codigo);
$(document).on('keydown', null, 'f8', call_consultar_produto_descricao);
// $(document).on('keydown', null, 'f8', call_contas_a_pagar);
$(document).on('keydown', null, 'f9', call_contas_a_receber);
// $(document).on('keydown', null, 'f10', call_relatorios);
$(document).on('keydown', null, 'f10', call_pesquisar_prod);
$(document).on('keydown', null, 'return', inserir_item);
$(".produtos_codigo_only").on('keydown', null, 'return', inserir_item);
$("#codprodmodal").on('keydown', null, 'return', mostrar_detalhes);
$("#detalharprod").on('keypress', null, 'return', mostrar_detalhes);

$('form').on('keydown', "input", function(e) {
  //verifica se o campo é o de quantidades e de tiver pressionado um enter passa para o proximo campo
  if(e.which === 13 && $(e.target).hasClass('quantidades') && $(e.target).is(":focus")) {
    var inputs = $(e.target).closest('form').find(':input:visible');
    inputs.eq( inputs.index(e.target)+ 1 ).focus();
  }
  //verifica se o campo é o de valor unitario e de tiver pressionado um enter passa para o campo do codigo novamente
  if(e.which === 13 && $(e.target).hasClass('valor_item') && $(e.target).is(":focus")) {
    $(".produtos_codigo_only").focus();
  }
  return e.which !== 13;
});

$('body').on('keydown', "input", function(e) {
  return e.which !== 116;
});

$('body').on('keydown', "input", function(e) {
  return e.which !== 117;
});
$('body').on('keydown', "input", function(e) {
  return e.which !== 114;
});
$('body').on('keydown', "input", function(e) {
  return e.which !== 123;
});
$('body').on('keydown', "input", function(e) {
  return e.which !== 112;
});

function inserir_item(e){
  e.preventDefault();
  if($(".produtos_codigo_only").val()>0){
    $(".inserir_item").click();
    setTimeout(() => {
      $("table#table_produtos > tbody").find('input.quantidades').last().focus();
    }, 200)
    
  }
}
function call_no_action(e){
  e.preventDefault();
}

function call_consultar_produto_codigo(e){
  e.preventDefault();
  $('.produtos_codigo_only').focus();//data('chosen').activate_action();
  return false;
}

function call_consultar_produto_descricao(e){
  e.preventDefault();
  $('.produtos_descricao_only').data('chosen').activate_action();
  return false;
}

function call_vendas(e){
  e.preventDefault();
  if(confirm('Desejas ir para vendas?'))
  window.location.href = $urlserver+'/vendas';
  return false;
}
function call_contas_a_pagar(e){
  e.preventDefault();
  if(confirm('Desejas ir para contas a pagar?'))
  window.location.href = $urlserver+'/financeiro/contasapagar';
  return false;
}
function call_contas_a_receber(e){
  e.preventDefault();
  if(confirm('Desejas ir para contas a receber?'))
  window.location.href = $urlserver+'/financeiro/contasareceber';
  return false;
}
function call_relatorios(e){
  e.preventDefault();
  if(confirm('Desejas ir para relatórios?'))
  window.location.href = $urlserver+'/relatorios';
  return false;
}

function getallprodutostomodal(selector){
  $.ajax({
    url: $urlserver+"/produtos/all",
    type: "get",
    success: function(data){
      data = JSON.parse(data);
      var $str = "", $str2 = "";
      if(data.length == 0){
        $str = "<option value=''>Nenhum produto cadastrado</option>";
      }
      else{
        $str = "";//<option value=''>Selecione um produto pela descrição</option>"
        $.each(data, function(key){
          if(!data[key].desabilitar) $str += "<option value='"+data[key].id+"'>"+data[key].titulo+"</option>";
        });
      }
      $(selector).html($str);
      //  $("table#table_produtos > tbody tr:eq("+$numero_linhas+")").find('select').html($str);
      // $('.produtos_codigo_only').chosen({enable_split_word_search:true,search_contains: false,no_results_text:"Código não encontrado!"});
      // $(selector).chosen({search_contains: true});//{search_contains: true,no_results_text:"Descrição não encontrada!"});
      $(selector).chosen({search_contains: true,width: "inherit"});
      $(selector).data('chosen').activate_action();
    },
    error: function(data){
      console.log(data.message);
    }
  });

}
$("#modal-pesquisar-prod").on('shown.bs.modal', function () {
  $("#codprodmodal").val('');
  getallprodutostomodal("select#select_modal");
});
function mostrar_detalhes(){
  prodsel = $("#codprodmodal").val();
  if(prodsel==""){
    prodsel = $("select#select_modal").val();
  }

  if(prodsel!=""){
    $('select#select_modal').val(prodsel).trigger("chosen:updated");
    $.ajax({
      url: $urlserver+"/produtos/"+prodsel,
      type: "get",
      success: function(data){
        data = JSON.parse(data);
        var produto = data.produtos[0];
        if(produto.desabilitar){
          str = '<div class="jumbotron"><h4>Produto desabilitado!</h4></div>';
          $("#prod-space-modal").html(str);
        }else{
          var preco_custo = (produto.preco*(1 - (produto.valor_agregado/100.0)))
          str = '<div class="jumbotron"><span class="badge">#'+produto.id+'</span><h4>'+produto.titulo+'</h4>'+
          '<h3 style="text-align: right">Preço: R$<span class="dinheiro valor_item">'+produto.preco+'</span></h3>';
          if(data.user==="root" || data.user==="administrador" || data.user==="gerente" ) {
            str += '<h4 style="text-align: right">Custo: R$<span class="dinheiro valor_item">'+preco_custo+'</span></h4>';
            str += '<h4 style="text-align: right">Agregado: <span class="dinheiro valor_item">'+produto.valor_agregado+'%</span></h4>';
          }
          str += '</div><div id="historico_busca"></div>';
          //  str = '<input type="text" class="form-control" value="'+produto.codigo+'">';
          //  str += '<input type="text" class="form-control" value="'++'">';
          //  str += '<input type="text" class="form-control dinheiro valor_item" placeholder="0,00" value="'+produto.preco+'">';
          $("#prod-space-modal").html(str);
          $('.dinheiro').autoNumeric("init",{
            aSep: '.',
            aDec: ','
          });
          $("#codprodmodal").val('');
          // saveHistoricoBusca(data[0]);

        //   var fdata = new FormData();
        // fdata.append('prod_id', produto.id);
        // fdata.append('user_id', produto.id); se precisar passa o id do usuario
        // fdata.append('parcelasvalor[]', getObjValueWithoutMask($("#parcelasvalor"+i)));
      // console.log( $("#parcelasvalor"+i).val());
      // fdata.append('parcelasobs[]', $("#parcelasobs"+i).val());
    // var route = $("form").attr("action");
    

          $.ajax({
            url: $urlserver+"/historico_busca_produto/"+produto.id,
            type: 'get',
            // data: fdata,
            dataType: 'json',
            // contentType: false,
            // processData: false,
            // data: {produto_id: produto.id, _token :token},
            success: function(data){
              var inner_html = '<p>Últimas buscas</p>';
              $.each(data, function(i,v){
                inner_html += '<span class="btn btn-link go_to_search" data-id="'+data[i].id+'">COD: '+data[i].id+" - "+data[i].titulo+"</span> ";
              });
              $("#historico_busca").html(inner_html);
            },
            error: function(data){
              console.log('erro' ,data);
            }
          });
        }
      },
      error: function(data){
        console.log(data);
      }
    });

  }else $("#prod-space-modal").html("");
  $("#codprodmodal").focus();
}
$('body').on('click', '.go_to_search', function(){
  var id = $(this).data('id');
  $("#codprodmodal").val(id);
  mostrar_detalhes();
});
$("#detalharprod").on("click", mostrar_detalhes);
$("#btn-open-search-modal").click(call_pesquisar_prod);
function call_pesquisar_prod(e){
  e.preventDefault();
  $("#modal-pesquisar-prod").modal('show');
}
function call_venda_nova(e){
  e.preventDefault();
  if(confirm('Desejas ir para nova venda?'))
  window.location.href = $urlserver+'/vendas/create';
  return false;
}
// function call_consultar_produto(e){
//   e.preventDefault();
//   var passe = true;
//   if($("table#table_produtos").length > 0){
//       $("table#table_produtos > tbody tr").each(function(){
//         var select_prod = $(this).find('select');
//         if(select_prod.val() == ""){
//           select_prod.select2('open');
//           passe = false
//           return passe;
//         }
//       });
//       if(passe){
//         $("#add_produto_row").click();
//         call_consultar_produto();
//       }
//
//   }
//   return false;
// }
function call_vendas_confirmar(e){
  e.preventDefault();
  if($("#confirmarVendaBt").length > 0) $("#confirmarVendaBt").click();
  return false;
}
function call_cancelar_venda(e){
  e.preventDefault();
  if($("#cancelar_venda_nova").length > 0){
    if(confirm('Desejas realmente cancelar?'))
    window.location.href = $urlserver+'/vendas';
  }
  return false;
}
function call_produtos(e){
  e.preventDefault();
  if(confirm('Desejas ir para produtos?'))
  window.location.href = $urlserver+'/produtos';
  return false;
}

$("body").on('click', '.updatecampos', function(){
  getAllUnidades();
  getAllImpostos();
  getAllCategorias();
  getAllFornecedores();
});

$(".total_venda_load_pg").keyup(function(){
  $("#tipopagamentos").trigger("change");
}).change();

$("#submit_new_imposto, #submit_edit_imposto").on('click', function(e){
  e.preventDefault();
  $("#valor_imposto_hidden").val(getObjValueWithoutMask($("#valor_imposto")));
  $("#imposto_form").submit();
});

$('input[type="text"]').on('blur',function(){
  $(this).val($(this).val().toUpperCase());
});

$('.produtos_codigo_only').change(function(){
  $('.produtos_descricao_only').val($(this).val()).trigger("chosen:updated");
  $('.inserir_item').focus();
});
$('.produtos_descricao_only').change(function(){
  $('.produtos_codigo_only').val($(this).val());//.trigger("chosen:updated");
  $('.inserir_item').focus();
});
$('.codprodmodal').change(function(){
  $('.select_modal').val($(this).val()).trigger("chosen:updated");
  $('#detalharprod').focus();
});
$('.select_modal').change(function(){
  $('.codprodmodal').val($(this).val());//.trigger("chosen:updated");
  $('#detalharprod').focus();
});
// $("#editing_product").change(function(){
//   return;
// });

$("select#impostos").select2();

// $("body").on("click",".lock-button",function(){
//   $(this).html("Carregando...");
//   $(this).attr("disabled","disabled");
//   $('#form_edit_venda').submit();
// });

$(document).keypress(function(event){
  if(event.keyCode == 13){
    if($('#modal-pesquisar-prod > .select_modal').val() != "")
    $('#detalharprod').click();
  }
});

$(document).on('blur',".quantidades, .valor_item",function(){
  // console.log(parseFloat(this.value));
  if($.trim(this.value)=="" || this.value == 0.0 || this.value == "0,00" || this.value == "0"){
    $("#confirmarVendaBt").attr('disabled','true');
    //setTimeout('alert("Verificar se quantidade/preço do produto está inválido!");', 1);
    console.log($(this).parent().parent().find('p').first())
    $(this).parent().parent().find('p').first().text('Quantidade inválida!');
//     <p class="help" style="
//     font-size: xx-small;
//     text-align: -webkit-center;
//     color: #f00;
// ">Quantidade inválida!</p>
    this.style.borderColor = 'red';
  }else{
    $(this).parent().parent().find('p').first().text('');
    $("#confirmarVendaBt").removeAttr('disabled');
    this.style.borderColor = '#ccc';
  }
});

$('#sel_conferente').change(function() {
  if($(this).is(":checked")) {
    $('#funcionarios').prop('disabled', false);
    $('#funcionarios').select2();
  }else{
    $('#funcionarios').select2('destroy');
    $('#funcionarios').prop('disabled', true);
    $('#funcionarios').select2();
    // $('input["pessoa_conferente_id"]').attr('disabled','disabled');
  }
}).change();

});
if ($('.chosen-container').length > 0) {
  $('.chosen-container').on('touchstart', function(e){
    e.stopPropagation(); e.preventDefault();
    // Trigger the mousedown event.
    $(this).trigger('mousedown');
  });
}
$("body").on("click", "button.habilitar", function(d){
  var url = $(this).attr('url');
  if(confirm("Desejas realmente habilitar este produto?"))
    window.location.replace(url);
  return false;
});

$("body").on("click", "button.desabilitar", function(d){
  var url = $(this).attr('url');
  if(confirm("Desejas realmente desabilitar este produto?"))
    window.location.replace(url);
  return false;
});


$(document).ready(function(){
  $("button.habilitar-prod").parent().parent().css("background-color","red");
  $("select#estados").chosen();
});

$("body").on("click", "button#atalho-nova-venda", function(e){
    var url = $(this).attr('url');
    if( !( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )){
      if(confirm('Desejas realmente prosseguir para nova venda?'))
        window.location.replace(url);
      return false;
    }else {
      window.location.replace(url);
    }
});

$("body").on("click", "button#atalho-ver-vendas", function(e){
    var url = $(this).attr('url');
    if( !( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )){
      if(confirm('Desejas realmente prosseguir para listagem de todas as vendas?'))
        window.location.replace(url);
      return false;
    }else {
      window.location.replace(url);
    }
});

$("body").on("click", "button#atalho-novo-cliente", function(e){
    var url = $(this).attr('url');
    if( !( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )){
      if(confirm('Desejas realmente prosseguir para cadastro de novo cliente?'))
        window.location.replace(url);
      return false;
    }else {
      window.location.replace(url);
    }
});
