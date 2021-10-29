docker run  -it --rm -u $(id -u):$(id -g) -v $(pwd):/app composer "$@"
