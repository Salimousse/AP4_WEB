<!DOCTYPE html>
<html>
<head>
    <title>Interventions</title>
</head>
<body>
    <h1>Interventions Chatbot</h1>
    <p>Nombre de conversations: {{ $conversations->count() }}</p>
    @foreach($conversations as $conversation)
        <div>
            <h3>Conversation {{ $conversation->conversation_id }}</h3>
            <p>Dernier message : {{ $conversation->messages->last()->content ?? 'Aucun' }}</p>
            <a href="{{ route('admin.intervention.show', $conversation->id) }}">Voir et répondre</a>
        </div>
    @endforeach
</body>
</html>