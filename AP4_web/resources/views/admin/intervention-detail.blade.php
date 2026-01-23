<!DOCTYPE html>
<html>
<head>
    <title>Détail Conversation</title>
</head>
<body>
    <h1>Détail Conversation {{ $conversation->conversation_id }}</h1>
    <h3>Messages</h3>
    @foreach($conversation->messages as $message)
        <div>
            <strong>{{ ucfirst($message->sender) }} :</strong> {{ $message->content }}
        </div>
    @endforeach

    <form method="POST" action="{{ route('admin.intervention.respond', $conversation->id) }}">
        @csrf
        <textarea name="message" placeholder="Votre réponse..."></textarea>
        <button type="submit">Envoyer</button>
    </form>
</body>
</html>