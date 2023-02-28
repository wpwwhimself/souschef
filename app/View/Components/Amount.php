<?php

namespace App\View\Components;

use App\Models\Ingredient;
use Illuminate\View\Component;

class Amount extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $output;
    public $ingredient;
    public function __construct(public $id)
    {
        $this->ingredient = Ingredient::findOrFail($id);

        if($this->ingredient->template->unit != "JNO"){
            $this->output = $this->ingredient->amount . " " . $this->ingredient->template->unit;
        }else{
            $rem = $this->ingredient->amount - floor($this->ingredient->amount);
            $this->output = floor($this->ingredient->amount) ?: "";
            if($rem == 0)         $this->output .= "░░░";
            else if($rem <= 0.25) $this->output .= "░░█";
            else if($rem <= 0.5)  $this->output .= "░██";
            else if($rem <= 1)    $this->output .= "███";
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.amount');
    }
}
