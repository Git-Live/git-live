Will reset all additions and changes to the current branch.

If <info><path></info> is specified, additional files and directories will be removed under the specified <info><path></info>.
If <info><path></info> is not specified,<info><path></info> will specify the top-level directory.

In fact, you can use

<fg=cyan>git reset --hard HEAD</>
<fg=cyan>git clean -df <path></>

in succession.
