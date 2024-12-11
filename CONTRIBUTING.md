
## Submit bugs:

- go to GitHub project page : https://github.com/theowenger/oc_inazanoui
- click on "Issues"
- verify if your bug already exist
- if not, click on "Next Issue"
- choose label "Bug"
- Write title and description of your bug
- describe maximum information on your disposition to recreate bug
- If bug concern front, drop a screenshot in the attached file
- If you know which part of code is concern, paste the code in `` tag
- If you are PO, you can Assign dev in the issue correction

## Propose functionalities:
- go to GitHub project page : https://github.com/theowenger/oc_inazanoui
- click on "Issues"
- verify if your feature issue already exist
- if not, click on "Next Issue"
- choose Label "Enhancement"
- write title and description of your feature

## Add feature:

When you add code, beware of this criticals points:

- create new branch "feature/name_of_your_branch"
- If your code depend on Entity, generate new migration with doctrine
- If your code depend on Controller, make sure you are on the good controller (ex: mediaController for media's code)
- When your feature is ready, it's time to write test

## Add Tests:

- for every feature, is essential to write test before merge into develop
- if existing test need to be rework, make it before create new test
- write unit/functionnal test
- launch test (see README.md)

## finish feature:

- Once feature + test is over, it's time to finish your git branch
- git checkout develop
- git pull
- git checkout "name_of_your_branch"
- git merge develop
- resolve conflict
- launch test
- git checkout develop
- git merge "name_of_your_branch"
- git delete "name_of_your_branch"
- git delete -D "name_of_your_branch"

## Add doc:
If your modifications need to be known by others contributors, add explanations in README.md / CONTRIBUTING.md