<p>
    An invite was sent from <strong>{{ $user->firstname }} {{ $user->lastname }}</strong> in order to join the <strong>fairSCRIBE</strong> team <strong>{{ $team->name }}</strong>.
</p>
<p>
    You can visit the link below in order to register and accept or reject the invite: <br />
    {{ env('SCRIBE_REGISTER_URL') }}
</p>
