<?php

/**
 * This file is part of Git-Live
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 * @category   GitCommand
 * @package    Git-Live
 * @subpackage Core
 * @author     akito<akito-artisan@five-foxes.com>
 * @author     suzunone<suzunone.eleven@gmail.com>
 * @copyright  Project Git Live
 * @license    MIT
 * @version    GIT: $Id\$
 * @link       https://github.com/Git-Live/git-live
 * @see        https://github.com/Git-Live/git-live
 */

namespace GitLive\Driver;

use GitLive\Support\Collection;

class BranchDriver extends DriverBase
{
    /**
     * @return Collection
     */
    public function branchList()
    {
        $branch = $this->GitCmdExecutor->branch([], true);

        return $this->makeArray($branch);
    }

    /**
     * @return Collection
     */
    public function branchListAll()
    {
        $branch = $this->GitCmdExecutor->branch(['-a'], true);

        return $this->makeArray($branch);
    }

    /**
     * @param $branch
     * @return Collection
     */
    private function makeArray($branch)
    {
        $branch = explode("\n", trim($branch));

        array_walk($branch, function (&$item) {
            $item = trim(mb_substr($item, 1));
        });

        return collect($branch);
    }
}
