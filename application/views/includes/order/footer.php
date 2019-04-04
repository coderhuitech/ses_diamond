</div>
        	
       </div>
      <div id="footer">
      <?php
		$date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
 		echo 'Time : '. $date->format('d/m/Y H:i:s');
	  ?>
      </div>
    </div>
</body>

</html>
