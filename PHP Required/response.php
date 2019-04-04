	if($result['success']==1){
			echo '<div id="msg">';
			echo "Saved Successfully";
			echo '</div>';
			echo '<div id="error">noerror</div>';
			echo '<div id="report">';
			echo 'Transation ID ';
			echo '</div>';
		}else{
			echo '<div id="msg">';
			echo "Transaction Saving Error";
			echo '</div>';
			echo '<div id="error">error</div>';
			echo '<div id="report">';
			print_r($result);
			echo '</div>';
		}
	}