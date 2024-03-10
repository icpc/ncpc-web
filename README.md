# NCPC website

This is a jekyll site.

Given that you have a working ruby installation with the ruby manager `gem`, install dependencies with:

- `gem install jekyll`
- `gem install jekyll-redirect-from`

Run it locally with `jekyll serve`

NCPC data (testdata, judges solutions, problem pdfs and solution slides) are published as releases in the github repo: [https://github.com/icpc/ncpc-web](https://github.com/icpc/ncpc-web).

On 2023-01-29 Måns performed a filter repo to remove all old pdfs, zips and .tar.bz2 files from teh repo (making cloning and deployment very slow). If you had a clone of this repo before that date you can't get the new changes with `git pull` instead you need to do:

```
git fetch
git reset --hard origin/master
```
