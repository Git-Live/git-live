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

namespace GitLive\Command\Log;

use GitLive\Command\CommandBase;
use Symfony\Component\Console\Input\InputOption;

class BaseLogCommand extends CommandBase
{
    protected function configure()
    {
        parent::configure();
        $this
            ->addOption('oneline', '', InputOption::VALUE_NONE, __('This is a shorthand for "--pretty=oneline --abbrev-commit" used together.'))
            ->addOption(
                'abbrev-commit',
                '',
                InputOption::VALUE_NONE,
                __('Instead of showing the full 40-byte hexadecimal commit object name, show only a partial prefix. Non default number of digits can be specified with "--abbrev=<n>" (which also modifies diff output, if it isdisplayed).')
                . "\n"
                . __('This should make "--pretty=oneline" a whole lot more readable for people using 80-column terminals.')
            )
            ->addOption('no-abbrev-commit', '', InputOption::VALUE_NONE, __('Show the full 40-byte hexadecimal commit object name. This negates --abbrev-commit and those options which
           imply it such as "--oneline". It also overrides the log.abbrevCommit variable.'))
            ->addOption('merges', '', InputOption::VALUE_NONE, __('Print only merge commits. This is exactly the same as --min-parents=2.'))
            ->addOption('no-merges', '', InputOption::VALUE_NONE, __(' Do not print commits with more than one parent. This is exactly the same as --max-parents=1.'))
            ->addOption('no-min-parents', '', InputOption::VALUE_NONE, __('reset these limits (to no limit) again. Equivalent forms are --min-parents=0 (any commit has 0 or more parents) and --max-parents=-1 (negative numbers denote no upper limit).'))
            ->addOption('no-max-parents', '', InputOption::VALUE_NONE, __('reset these limits (to no limit) again. Equivalent forms are --min-parents=0 (any commit has 0 or more parents) and --max-parents=-1 (negative numbers denote no upper limit).'))
            ->addOption('min-parents', '', InputOption::VALUE_REQUIRED, __('Show only commits which have at least (or at most) that many parent commits.')
                . __('In particular, --max-parents=1 is the same as --no-merges, --min-parents=2 is the same as --merges.')
                . __('--max-parents=0 gives all root commits and --min-parents=3 all octopus merges.'))
            ->addOption('max-parents', '', InputOption::VALUE_REQUIRED, __('Show only commits which have at least (or at most) that many parent commits.')
                . __('In particular, --max-parents=1 is the same as --no-merges, --min-parents=2 is the same as --merges.')
                . __('--max-parents=0 gives all root commits and --min-parents=3 all octopus merges.'))
            ->addOption(
                'graph',
                '',
                InputOption::VALUE_NONE,
                __('Draw a text-based graphical representation of the commit history on the left hand side of the output.') . "\n"
                . __('This may cause extra lines to be printed in between commits, in order for the graph history to be drawn properly. Cannot be combined with --no-walk.') . "\n"
                . __('This enables parent rewriting, see History Simplification above.') . "\n"
                . __('This implies the --topo-order option by default, but the --date-order option may also be specified.')
            )
            ->addOption('topo-order', '', InputOption::VALUE_NONE, __('Show no parents before all of its children are shown, and avoid showing commits on multiple lines of history intermixed.'))
            ->addOption('date-order', '', InputOption::VALUE_NONE, __('Show no parents before all of its children are shown, but otherwise show commits in the commit timestamp order.'))
            ->addOption('author-date-order', '', InputOption::VALUE_NONE, __('Show no parents before all of its children are shown, but otherwise show commits in the author timestamp order.'))
            ->addOption('reverse', '', InputOption::VALUE_NONE, __('Output the commits chosen to be shown (see Commit Limiting section above) in reverse order. Cannot be combined with --walk-reflogs.'))
            ->addOption(
                'walk-reflogs',
                'g',
                InputOption::VALUE_NONE,
                __('Instead of walking the commit ancestry chain, walk reflog entries from the most recent one to older ones.') .
                __('When this option is used you cannot specify commits to exclude') .
                __(' (that is, ^commit, commit1..commit2, and commit1...commit2 notations cannot be used).')
            )
            ->addOption(
                'pretty',
                '',
                InputOption::VALUE_REQUIRED,
                __('Pretty-print the contents of the commit logs in a given format, where <format> can be one of oneline, short, medium, full, fuller, email, raw, format:<string> and tformat:<string>.') . "\n"
                . __('When <format> is none of the above, and has %placeholder in it, it acts as if --pretty=tformat:<format> were given.')
            )
            ->addOption('format', '', InputOption::VALUE_REQUIRED, __(''))
            ->addOption(
                'diff-filter',
                '',
                InputOption::VALUE_REQUIRED,
                __('Select only files that are Added (A), Copied (C), Deleted (D), Modified (M), Renamed (R), have their type (i.e. regular file, symlink, submodule, ...) changed (T), are Unmerged (U), are Unknown (X), or have had their pairing Broken(B).') . "\n"
                . __('Any combination of the filter characters (including none) can be used.') . "\n"
                . __('When * (All-or-none) is added to the combination, all paths are selected if there is any file that matches other criteria in the comparison; if there is no file that matches other criteria, nothing is selected.')
            )
            ->addOption(
                'name-status',
                '',
                InputOption::VALUE_NONE,
                __('Show only names and status of changed files. See the description of the --diff-filter option on what the status letters mean.')
            )
            ->addOption(
                'decorate',
                '',
                InputOption::VALUE_OPTIONAL,
                __('[short|full|auto|no]') . "\n"
                . __('Print out the ref names of any commits that are shown.') . "\n"
                . __('If short is specified, the ref name prefixes refs/heads/, refs/tags/ and refs/remotes/ will not be printed.') . "\n"
                . __('If full is specified, the full ref name (including prefix) will be printed.') . "\n"
                . __('If auto is specified, then if the output is going to a terminal, the ref names are shown as if short were given, otherwise no ref names are shown.') . "\n"
                . __('The default option is short.')
            )
            ->addOption(
                'patch',
                'p',
                InputOption::VALUE_NONE,
                __('Generate patch (see section on generating patches).')
            )
            ->addOption(
                'no-patch',
                's',
                InputOption::VALUE_NONE,
                __('Suppress diff output. Useful for commands like git show that show the patch by default, or to cancel the effect of --patch.')
            )
            ->addOption(
                'raw',
                '',
                InputOption::VALUE_NONE,
                __('For each commit, show a summary of changes using the raw diff format.') .
                __('See the "RAW OUTPUT FORMAT" section of git-diff(1).')
                . __('This is different from showing the log itself in raw format, which you can achieve with --format=raw.')
            )
            ->addOption(
                'patch-with-raw',
                '',
                InputOption::VALUE_NONE,
                __('Synonym for -p --raw.')
            );

        $this->addOption(
            'indent-heuristic',
            '',
            InputOption::VALUE_NONE,
            __('Enable the heuristic that shift diff hunk boundaries to make patches easier to read.')
            . __('This is the default.')
        )
            ->addOption(
                'no-indent-heuristic',
                '',
                InputOption::VALUE_NONE,
                __('Disable the indent heuristic.')
            )
            ->addOption(
                'minimal',
                '',
                InputOption::VALUE_NONE,
                __('Spend extra time to make sure the smallest possible diff is produced.')
            )
            ->addOption(
                'patience',
                '',
                InputOption::VALUE_NONE,
                __('Generate a diff using the "patience diff" algorithm.')
            )
            ->addOption(
                'histogram',
                '',
                InputOption::VALUE_NONE,
                __('Generate a diff using the "histogram diff" algorithm.')
            );
    }
}
