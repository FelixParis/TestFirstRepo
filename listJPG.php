<?php

/**
 * Output a string describing a file tree filtered by the '.jpg' extension.
 *
 * @usage php listJPG.php <path> <hideEmptyDirectory>
 *
 * @example "php listJPG.php /home"
 * @example "php listJPG.php /home hide"
 */
echo listRecurseJPG($argv[1] ?? '.', $argv[2] ?? false);


/**
 * Returns a string describing a file tree filtered by the '.jpg' extension.
 *
 * @param $pPath Entry path
 * @param bool $pHideEmptyDirectories (optionnal) Hide empty directory flag if set on true
 * @param int $pDepth internal usage
 *
 * @return string Return file tree
 */
function listRecurseJPG($pPath, $pHideEmptyDirectories = false, $pDepth = 0)
{
	$output = '';

	if (! @is_dir($pPath) )
	{
		die('Parameter given is not a directory. (\''.$pPath.'\')');
	}

	if (! $dir = @opendir($pPath) )
	{
		die('Directory access error. (\''.$pPath.'\')');
	}

	/**
	 * Loop on each directory entry.
	 */
	while ($currentEntry = readdir($dir))
	{
		/**
		 * Bypass specials entries.
		 */
		if ( ($currentEntry != '.') && ($currentEntry != '..') )
		{
			/**
			 * Temporary storage (and format) current entry.
			 */
			$bufferCurrentEntry = str_repeat( str_repeat(' ',7), $pDepth) . $currentEntry . "\n";

			$currentEntryPath = $pPath.'/'.$currentEntry;

			if (is_dir($currentEntryPath))
			{
				/**
				 * Recurse call for any directory entry
				 */
				$bufferChilds = listRecurseJPG($currentEntryPath, $pHideEmptyDirectories, ++$pDepth);
				$pDepth--;

				/**
				 * Output current entry buffer and child buffer.
				 * If $pHideEmptyDirectories flag on, bypass output if no child found.
				 */
				if(! ($pHideEmptyDirectories && $bufferChilds == ''))
				{
					$output.= $bufferCurrentEntry . $bufferChilds;
				}
			}
			else
			{
				/**
				 * Output current entry buffer for JPG extension file
				 */
				if( strtolower(pathinfo($currentEntryPath, PATHINFO_EXTENSION)) == 'jpg')
				{
					$output.= $bufferCurrentEntry;
				}
			}
		}
	}

	return($output);
}