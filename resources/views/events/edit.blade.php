@extends('layouts.main')
@section('title','Editando:'.$events->title)
@section('content')



<div id="event-create-container" class="col-md6-offset-3" >
<h1>Editando: {{$events->title}}</h1>

<form action="/events/update/{{$events->id}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-group" >
        <label for="image">Imagem do Evento:</label>
     <input type="file" id="image" name="image" class="form-control-file">
     <img src="/img/events/{{$events->image}}" alt="{{ $events->title}}" class="img-preview">
    </div>




    <div class="form-group" >
        <label for="title">Evento:</label>
        <input type="text" class="form-control" id ="title" name="title" placeholder="Nome do Evento" value="{{$events->title}}"> 
    </div>


    <div class="form-group">
        <label for="date">Data do evento:</label>
        <input type="date" class="form-control" id ="date" name="date" value="{{$events->date->format('Y-m-d')}}"> 
    </div>
    
    <div class="form-group">
        <label for="title">Cidade:</label>
        <input type="text" class="form-control" id ="city" name="city" placeholder="Local do evento" value="{{$events->city}}"> 
    </div>
    

    <div class="form-group">
        <label for="title">O evento é privado?</label>
        <select name="private" id="private" class="from-control">
        <option value="0">Não</option>
        <option value="1" {{$events->private == 1 ? "selected='selected'" : ""}}>Sim</option>
        </select>
    </div>

    <div class="form-group">
        <label for="title">Descrição:</label>
        <input type="text" class="form-control" id ="description" name="description" placeholder="Oque vai acontecer no evento" value="{{$events->description}}"> 
    </div>

    <div class="form-group">
        <label for="title">Adicione itens de infraestrutura:</label>
            <div class="from-group">
                <input type="checkbox" name="items[]" value="Cadeiras"> Cadeiras
            </div>

            <div class="from-group">
                <input type="checkbox" name="items[]" value="Palco"> Palco
            </div>
            <div class="from-group">
                <input type="checkbox" name="items[]" value="Cerveja grátis"> Cerveja grátis
            </div>

            <div class="from-group">
                <input type="checkbox" name="items[]" value="Open food"> Open food
            </div>

            <div class="from-group">
                <input type="checkbox" name="items[]" value="Brindes"> Brindes
            </div>
            
    </div>

    
    <input type="submit" class="btn btn-primary" value="Editar Evento" style="margin-top:10px;">
</form>

</div>
   



@endsection