<!DOCTYPE html>
<html>
<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}" />
    <head>
        <title>Produto</title>
        <style></style>

    </head>
    <body>
      @if(Session::has('success'))
    <div class="alert-box success">
        <h2>{{ Session::get('success') }}</h2>
    </div>
      @endif

        <form action="produto" method="post">
          <input type="hidden" name="id_produto">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="text" name="codigo" value="" placeholder="Codigo do produto"></br>
          <input type="text" name="codigo_ncm" value="" placeholder="Codigo NCM do produto"></br>
          <input type="text" name="titulo" value="" placeholder="Titulo do produto"></br>
          <input type="text" name="descricao" value="" placeholder="Descricao do produto"></br>
          <input type="text" name="referencia" value="" placeholder="Referencia do produto"></br>
          <input type="text" name="custo" value="" placeholder="Custo do produto"></br>
          <input type="text" name="preco" value="" placeholder="Preço do produto"></br>
          <input type="text" name="quantidade_estoque" value="" placeholder="Quantidade em estoque"></br>
          <input type="text" name="id_fornecedor_produto" value="" placeholder="ID do fornecedor"></br>
          <input type="text" name="id_categoria_produto" value="" placeholder="Categoria do produto"></br>
          <input type="text" name="id_sub_categoria_produto" value="" placeholder="Subcategoria do produto"></br>
          <input type="submit" value="Salvar">
        </form>
    </body>
</html>
