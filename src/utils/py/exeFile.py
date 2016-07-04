#!/usr/bin/env python
#-*- encoding: utf-8 -*-
#
# Scritp que realiza el análisis de un fichero ejecutable en formato PE
#
# Author: Diego Fernández Valero

import sys, pefile, pydasm, json, re

def getCode(pe):
	"""Obtiene la representación en código ensamblador de las instrucciones
	del fichero binario.

	Recibe un objeto PE, y devuelve un string con el código ASM"""
	code = ""
	ep = pe.OPTIONAL_HEADER.AddressOfEntryPoint
	ep_ava = ep + pe.OPTIONAL_HEADER.ImageBase
	data = pe.get_memory_mapped_image()[ep:]
	offset = 0
	l = long(len(data))
	while offset < l:
		 i = pydasm.get_instruction(data[offset:], pydasm.MODE_32)
		 if i is None:
		 	break
		 code += pydasm.get_instruction_string(i, pydasm.FORMAT_INTEL, ep_ava+offset) + "\n"
		 offset += int(i.length)
	return code


def getSections(pe):
	"""Obtiene un listado de las secciones de un archivo ejecutable, así como su dirección
	virtual, el tamaño de la sección, etc.

	Recibe un objeto PE, y devuelve un diccionario en el que las claves son el nombre de la
	sección, y el valor una tupla con los datos recuperados."""
	sections = {}
	pattern = re.compile('\.?\w+')
	for section in pe.sections:
		m = pattern.match(section.Name)
		if m is None:
			sectionName = section.Name
		else:
			sectionName = m.group()
		sections[sectionName] = [hex(section.VirtualAddress), section.Misc_VirtualSize, section.SizeOfRawData]


	return sections

def getDlls(pe):
	"""Obtiene un listado de las DLL que utiliza un fichero, y las funciones de cada DLL

	Recibe un objeto PE, y devuelve un diccionario, en el que las claves son la dll y sus valores
	son las funciones y sus direcciones de memoria"""
	imports = pe.DIRECTORY_ENTRY_IMPORT
	dlls = {}


	for _import in imports:
		regs = []
		for reg in _import.imports:
			regs.append( (hex(reg.address), reg.name) )
		dlls[_import.dll] = regs
	return dlls



if __name__ == "__main__":

	if len(sys.argv) <= 1:
		sys.exit(-1)
	elif len(sys.argv) == 2:
		sys.exit(-2)
	

	fichero = sys.argv[1]
	option = sys.argv[2]

	pe = pefile.PE(fichero)

	if option == "sections":
		print json.dumps(getSections(pe))
	elif option == "dll":
		print json.dumps(getDlls(pe))
	elif option == "code":

		print getCode(pe)