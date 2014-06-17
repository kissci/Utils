<?
print '<pre style="font-size: 16px;">';
function getDirectory( $path = '.', $level = 0 ) { 
	$ignore = array( 'cgi-bin', '.', '..' );
	$dh = @opendir( $path );
	while( false !== ( $file = readdir( $dh ) ) ){
		if( !in_array( $file, $ignore ) ){
			$spaces = str_repeat( ' ', ( $level * 4 ) );
			if( is_dir( "$path/$file" ) ){
				//echo "DIR: $path/$file<br/>\n";
				getDirectory( "$path/$file", ($level+1) );
				echo "<b>".$path."/".$file."</b>";
				$owner = posix_getpwuid(fileowner($path."/".$file));
				echo "  (".$owner['name'];
				echo "|".substr(sprintf('%o', fileperms($path."/".$file)), -4).")\n";
			} else {
				$chars = "[^a-zA-Z0-9\-\.\_]";

				echo "  ".$path."/".$file;
				$owner = posix_getpwuid(fileowner($path."/".$file));
				echo "  (".$owner['name'];
				echo "|".substr(sprintf('%o', fileperms($path."/".$file)), -4).")\n";
				if ( preg_match("/$chars/", $file) ) {
					$newfilename = preg_replace("/$chars/", '_', $file);
					if ( ! file_exists($newfilename) ) {
						echo '    <span style="color: red">->'.preg_replace("/$chars/", '_', $file)."</span>\n";
						rename ($path."/".$file, $newfilename);
					} else {
						echo '    <span style="color: red">NEM NEVEZHETÕ ÁT, mert már létezik a file.</span>'."\n";
					}
				}
			}
		}
	}
	closedir( $dh );
}
getDirectory( "." );
print "</pre>";

?>