<h2>Espace 
<?php
$session = session();
echo $session->get('user_role');
?> 
</h2>
<br />
<h2>Session ouverte ! Bienvenue
<?php
echo $session->get('user');
?> !
</h2>
