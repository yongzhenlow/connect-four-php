<?php 
/**
 * NOTICE OF LICENSE
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 * 
 * @copyright   Copyright (c) 2012
 * @license     http://opensource.org/licenses/MIT  The MIT License (MIT)
 */

/**
 * Connect Four
 *
 * @author     Low Yong Zhen <cephyz@gmail.com>
 */

/**
 * @TODO Currently no tracking and printing for number of pieces dropped into each col for each player.
 */

class ConnectFour {
	
	/**
	 * Default rows is 6
	 * 
	 * @var int
	 */
	protected $_rows = 6;

	/**
	 * Default columns is 6
	 * 
	 * @var int
	 */
	protected $_columns = 6;
	
	/**
	 * The board array to store information about player's pieces
	 * 
	 * @var array
	 */
	protected $_board_array = array();
	
	/**
	 * Player 1 = 1, Player 2 = 2, No Player Selected = 0
	 * 
	 * @var int
	 */
	
	protected $_current_player = 0;
	
	/**
	 * Track moves executed by both players.
	 * 
	 * @var int
	 */
	protected $_moves = 0;
	
	
	
	/**
	 * CONSTRUCTOR
	 * Starts the game on new instance
	 * @param int $rows
	 * @param int $cols
	 */
	function __construct( $rows = 6, $cols = 6){
		
		$this->_setDimensions( $rows, $cols );
		
		$this->_initGame();
	}
	
	/**
	 * Creates or resets a 2d board array
	 * 
	 * @desc This is a better upgrade for initializeGameBoard method as described in the assignment.
	 * Please note this method will not include a parameter since it creates the 2d array. (contrary to requirements)
	 * This method effectively creates/resets the gameboard, assigning values while creating, looping only once.
	 * 
	 * Alternatively, the assignment assumes you will use a static 6x6 board, in that case, which you can create a static 2d array and pass it to this function.
	 */
	protected function _initializeGameBoard(){
	
		//resets the board array
		$_board_array = array();
		
		for($i = 0; $i < $this->getRows() ; $i ++ ){
	
			$_board_array[$i] = array();
			
			for($j = 0; $j < $this->getColumns() ; $j ++ ){
			
				//-1 means this slot is unoccupied.
				$_board_array[$i][$j] = -1;
			
			}
	
		}
		
		$this->_setCurrentBoard($_board_array);
		
	}
	
	/**
	 * The game board is initialized here and first move will begin after starting player is set.
	 */
	protected function _initGame(){
		
		//Setup our game board
		$this->_initializeGameBoard();
		
		//Set a random player to start first
		$this->_setCurrentPlayer(rand(1,2));
		
		//start dropping pieces
		$this->_dropPiece();
		
	}
	
	/**
	 * Creates a 'move' for each player by randomly choosing a column to drop a piece into.
	 */
	protected function _dropPiece(){
		
		//Check if total moves reached. (Recursive baseline)
		if( $this->_moves >= ( $this->getRows() * $this->getColumns() )) {
			
			//No winner then =(
			$this->_showNoWinnerMessage();
			
			return false; 
		}
		
		//Random column chosen for placing chips
		$_target_col = rand(0, $this->getColumns()-1);
		$_current_board = $this->_getCurrentBoard();
		
		for( $row = $this->getRows()-1; $row>=0; $row-- ){
			
			//If slot is currently empty
			if( $_current_board[$row][$_target_col] === -1 ){
				
				//Set slot to current player
				$_current_board[$row][$_target_col] = $this->_getCurrentPlayer();
				
				//Update the no. of moves, might wana setter/getter this
				$this->_moves++;
				
				//Update the board
				$this->_setCurrentBoard($_current_board);
				
				//Print current board
				$this->_printBoard();
				
				//Check for winner
				if( $this->_checkForWinner( $row, $_target_col ) ){
					
					//If winner is found
					$this->_showWinnerMessage();
					
					return false;
					
				}else{
					
					//Else continue the game
					
					//Change player
					$this->_togglePlayer();
					
					//Drop the piece
					$this->_dropPiece();
					
				}
				
				//exit once a piece is dropped for this move
				return false;
				
			} 
			
		}
		
		//If it comes to here, it means no slots are empty (column is full). Redo move again
		$this->_dropPiece();
		
	}
	
	/**
	 * Print out each step (board and details)
	 */
	protected function _printBoard(){
		
		print '<p>Player '. $this->_getCurrentPlayer() .': Move No. ' . $this->_moves . '</p>';
		
		print '<table>';
		
		$_board_array = $this->_getCurrentBoard();
				
		for($i = 0; $i < $this->getRows() ; $i ++ ){
			
			print '<tr>';
			
			for($j = 0; $j < $this->getColumns() ; $j ++ ){
				
				//decoration
				$_class = "";
				
				if( $_board_array[$i][$j] === 1 ){
					//player 1 color
					$_class = "player-1";
					
				}else if( $_board_array[$i][$j] === 2 ){
					//player 2 color
					$_class = "player-2";
				
				}
					
				print '<td class="'.$_class.'" >' . $_board_array[$i][$j] . '</td>';
					
			}
			
			print '</tr>';
		
		}
		
		print '</table>';
	}
	
	/**
	 * Displays the message for the winner
	 */
	protected function _showWinnerMessage(){
		
		print '<p class="message">Player ' . $this->_getCurrentPlayer() .' wins the game!</p>';
		
	}
	
	/**
	 * Displays the message if there's no winner
	 */
	protected function _showNoWinnerMessage(){
	
		print '<p class="message">No winner for this round.</p>';
	
	}
	
	/**
	 * Switches the turn to the other player
	 */
	protected function _togglePlayer(){
		
		$this->_setCurrentPlayer($this->_getCurrentPlayer()===1?2:1);
		
	}

	/**
	 * Gets the player for the current turn
	 * 
	 * @return int
	 */
	protected function _getCurrentPlayer(){

		return $this->_current_player;	
			
	}
	
	/**
	 * Sets the player for the current turn
	 */
	protected function _setCurrentPlayer( $player_no ){
	
		$this->_current_player = $player_no;
			
	}
	
	/**
	 * Gets the current board array
	 * 
	 * @return array
	 */
	protected function _getCurrentBoard(){
	
		return $this->_board_array;
			
	}
	
	/**
	 * Sets the current board array
	 */
	protected function _setCurrentBoard( $board_array ){
	
		$this->_board_array = $board_array;
			
	}
	
	
	/**
	 * Check for winner
	 * 
	 * @return boolean
	 */
	protected function _checkForWinner( $row, $col ){
		
		if($this->_horizontalCheck($row, $col) 
				|| $this->_verticalCheck($row, $col) 
		){
			return true;
		}
		
		return false;
		
	}
	
	/**
	 * Check for horizontal pieces
	 * 
	 * @return boolean
	 */
	private function _horizontalCheck( $row, $col ){
		
		$_board_array = $this->_getCurrentBoard();
		$_player = $_board_array[$row][$col];
		$_count = 0;
		
		//count towards the left of current piece
		for ( $i = $col; $i>=0; $i-- )
		{
			
			if( $_board_array[$row][$i] !== $_player ){
				
				break;
				
			}
			
			$_count++;
			
		}
		
		//count towards the right of current piece
		for ( $i = $col + 1; $i<$this->getColumns(); $i++ )
		{
				
			if( $_board_array[$row][$i] !== $_player ){
		
				break;
		
			}
				
			$_count++;
				
		}
		
		return $_count>=4 ? true : false;
		
	}
	
	/**
	 * Check for vertical pieces
	 * 
	 * @return boolean
	 */
	private function _verticalCheck( $row, $col ){
	
		//if current piece is less than 4 pieces from bottom, skip check
		if ( $row >= $this->getRows()-3 ) {
			
			return false;
			
		}
		
		$_board_array = $this->_getCurrentBoard();
		$_player = $_board_array[$row][$col];
		
		for ( $i = $row + 1; $i <= $row + 3; $i++ ){
			
			if($_board_array[$i][$col] !== $_player){
				
				return false;	
				
			}
			
		}
		
		return true;
		
	}
	
	/**
	 * Set the number of rows and columns for the board
	 * 
	 * @param int $rows
	 * @param int $cols
	 */
	protected function _setDimensions($rows = 6, $cols = 6){
		
		if(!isset($rows)) return;
		
		$this->setRows($rows);
		$this->setColumns($cols===null?$rows:$cols);
		
	}
	
	/**
	 * Set the number of rows for the board
	 * 
	 * @param int $rows
	 */
	public function setRows($rows = 6){
	
		 $this->_rows = $rows;
	
	}
	
	/**
	 * Get the number of rows for the board
	 * 
	 * @return int
	 */
	public function getRows(){
		
		return $this->_rows;
		
	}
	
	/**
	 * Set the number of columns for the board
	 * 
	 * @param int $col
	 */
	public function setColumns($col = 6){
	
		$this->_columns = $col;
	
	}
	
	/**
	 * Get the number of columns for the board
	 * 
	 * @return int
	 */
	public function getColumns(){
	
		return $this->_columns;
	
	}
	
}//end: ConnectFour
?>