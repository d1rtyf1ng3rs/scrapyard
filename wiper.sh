#!/bin/bash

function dropBranch {
    branch1=$1
    echo "[Dropping branch: $branch1]"
    res=$(git branch -d $branch1)
    if [[ $res == *"Deleted branch"* ]]
    then
        echo "[Local branch $branch1 deleted succesfully]"
        res2=$(git push origin --delete $branch1 2>&1)
        if [[ $res2 == *"[deleted]"* ]]
        then
            echo "[Remote branch $branch1 deleted successfully]"
        else
            if [[ $res2 == *"remote ref does not exist"* ]]
            then
                echo "[Remote branch $branch1 doen't exist (?)]"
            else
                echo "[Nonparsed remote delete desult: $res2]"
            fi
        fi
    else
        if [[ $res == *"not fully merged"* ]]
        then
            echo "[Error: branch $branch1 not fully merged (?)]";
        else
            echo "[Unknown error on deleting branch $branch1]"
        fi
    fi
}

## получаем все ветки, смердженные в master; отфильтровываем отметку текущей ветки звездочкой
branches=$(git branch --merged=master | grep -oe '[^*].*' | sed 's/ //g')
for branch1 in $branches
do
    ## master смерджен в master по определению. Его не сносим.
    if [ $branch1 != "master" ]
    then
        dropBranch $branch1
    fi
done