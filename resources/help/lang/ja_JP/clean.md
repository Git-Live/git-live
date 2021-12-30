現在のブランチへの追加や変更をすべてリセットします。
<info><path></info>が指定された場合、追加ファイル及び追加ディレクトリの削除は指定された<info><path></info>配下となります。
<info><path></info>が指定されない場合、<info><path></info>にはトップレベルディレクトリが指定されます。

実際には、

<fg=cyan>git reset --hard HEAD</>
<fg=cyan>git clean -df <path></>

を連続して実行したときと同じです。
