<?php
/*
    class Marker {
        const WRITE_USAGE = 5;
        public $canErase;
        private $color;
        private $colorquantity = 100;
        function __construct($color)
        {
            $this->color = $color;
        }
        function getColor() 
        {
            return $this->color;
        }
        function setColor($color) 
        {
            $this->color = $color;
        }
        function write()
        {
            $this->colorquantity = $colorquantity-self::WRITE_USAGE;
        }
    }
// ovo je instanca klase
    $black = new Marker('black');
    var_dump($black->getColor());
//    var_dump($tmp->color);
    var_dump($black->color);
    $green = new Marker('green');
    var_dump($green->color);

    class PermanentMarker extends Marker {
        public $canErase = 0;
    }
    $black = new PermanentMarker('black');
    var_dump($black->getColor());

    class TemporaryMarker extends Marker {
        public $canErase = 1;
    }
    $white = new TemporaryMarker('white');
    var_dump($white->getColor());
*/
    class Deck {
        const MAX_CARDS_QUANTITY = 52;
        private $cards = [];
        private $signs = ['spades','hearts','clubs','diamonds'];
        /**
         * generate random card.
         * 
         * @return Card
        */
        private function generateCard()
        {
            $sign = $this->signs[rand(0,3)];
            $number = rand(1,14);
            return new Card ($sign,$number);
        }   
        /**
         * validates random card.
         * 
         * @return boolean
        */    
        private function validateCard(Card $card)
        {
            if ($this->countCards() >= self::MAX_CARDS_QUANTITY) {
                return false;
            }
            if (isset($this->cards[$card->getSign()]) and in_array($card, $this->cards[$card->getSign()])) {
                return false;
            }
            return true;
        }
        public function countCards()
        {
            $count = 0;
            foreach ($this->cards as $cardType) {
                $count += count($cardType);
            }
            return $count;
        }
        public function deal()
        {
            $invalid = true;
            while($invalid) {
                $card = $this->generateCard();
                if ($this->validateCard($card)) {
                    $this->cards[$card->getSign()][] = $card;
                    $invalid = false;
                }
            } 
        }
        public function test()
        {
            for ($i = 1; $i < 53; $i++) {
                $this->deal();
            }
            var_dump($this->cards);
        }
    }
    $deck = new Deck;
    $deck->test();
    class Card {
        private $number;
        private $sign;

        public function __construct($number,$sign)
        {
            $this->number = $number;
            $this->sign = $sign;
        }

        public function getSign() 
        {
            return $this->sign;
        }

        public function getNumber()
        {
            return $this->number;
        }
    }



















?>
