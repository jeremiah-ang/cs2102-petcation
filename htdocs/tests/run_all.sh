for filename in *.test.py; do
	prefix=$(echo $filename| cut -d'.' -f 1)
	bash run.sh $prefix
done

wait