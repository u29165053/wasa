#!/usr/bin/env python
# -*- encoding: utf-8 -*-

import sys, hashlib

BLOCKSIZE = 65536

def getHash(hasher, f):
	"""Obtiene el hash de un fichero. Para ello debe recibir como primer parámetro un elemento de tipo hashlib. 

	Parámetros:
		hasher - 

	"""
	with open(f, 'rb') as afile:
		buf = afile.read(BLOCKSIZE)
		while len(buf) > 0:
			hasher.update(buf)
			buf = afile.read(BLOCKSIZE)
	return hasher.hexdigest()

if __name__ == "__main__":

	if len(sys.argv) < 3:
		# python hash.py (md5 | sha1 | sha256 | sha512) filename
		sys.exit(1)

	m = sys.argv[1].lower()
	f = sys.argv[2]

	if m == "md5":
		h = hashlib.md5()
		print getHash(h,f)
	elif m == "sha1":
		h = hashlib.sha1()
		print getHash(h,f)
	elif m == "sha256":
		h = hashlib.sha256()
		print getHash(h,f)
	elif m == "sha512":
		h = hashlib.sha512()
		print getHash(h,f)
	else:
		sys.exit(2)