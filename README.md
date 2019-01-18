# PHP Solitaire Decript

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/dd8d4635e8374705a492a982052b15d2)](https://app.codacy.com/app/coriumalpha/PhpSolitaireCipher?utm_source=github.com&utm_medium=referral&utm_content=coriumalpha/PhpSolitaireCipher&utm_campaign=Badge_Grade_Dashboard)

Solitaire Cipher's PHP Implementation

Fill `$deckUnparsed` with your 54 card ordered deck, spacing each element:

*   Joker A = `A`
  
*   Joker B = `B`
  
^
  
*   Rank; Ace = `A`, Ten = `T`, Jack = `J`, Queen =  `Q`, King = `K` and the remainings as a number in range (`2`, `9`) 
  
*   Suit; Clubs = `c`, Diamonds = `d`, Hearts = `h`, Spades = `s`.

Each element represents one card, for example:
"Queen of Spades, 3 of Hearts, 1 of Diamonds and Joker B" can be expressed as `Qs 3h 1d B`.
