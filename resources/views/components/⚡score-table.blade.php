<?php

use App\Models\Tournament;
use Livewire\Component;

new class extends Component {
    public Tournament $tournament;

    #[On('score-saved')]
    public function resfreshTable()
    {
        $this->tournament->unsetRelation('tennisMatches');
    }

    public function with()
    {

    }
};
?>

<div>
</div>
