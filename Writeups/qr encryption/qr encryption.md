# CTF Writeup: QR Encryption

You are first given a png called qr_encrytion. It is basically a very colorful QR code. 
What your job is to decode these. With analysis via pixie, we can conclude that all of the pixels’ RGB values have either 40s or 255s. Because there are three values per pixel, we can conclude that there are 3 qr codes (one where the 40 in the R value is turned black, with the rest turned white, etc.). There are two ways to do this: one is manually editing it in paint *cough* Spencer *cough* and the other is writing a python program utilizing PIL.
Let’s write a program.

```python
from PIL import Image

org = Image.open("qr_encryption.png") #Original image

#create the three qr images. 
qr1 = Image.new('RGB',(23,23))
qr2 = Image.new('RGB',(23,23))
qr3 = Image.new('RGB',(23,23))

#create maps
orgMap = org.load()
qr1Map = qr1.load()
qr2Map = qr2.load()
qr3Map = qr3.load()

for x in range(org.size[0]):
	for y in range(org.size[1]):
		r,g,b = orgMap[x,y]
		
		#if r is 40 then put black on qr1
		if r == 40:
			qr1Map[x,y] = 0,0,0
		else:
			qr1Map[x,y] = 255,255,255
		
		#if g is 40 then put black on qr2
		if g == 40:
			qr2Map[x,y] = 0,0,0
		else:
			qr2Map[x,y] = 255,255,255
		
		#if b is 40 then put black on qr3
		if b == 40:
			qr3Map[x,y] = 0,0,0
		else:
			qr3Map[x,y] = 255,255,255

#save the qr codes
qr1.save("qr1.png")
qr2.save("qr2.png")
qr3.save("qr3.png")
```

Lastly, we need to scan our qr codes. This reveals the flag:
{y0u_bett3r_not_hAve_brute4orced_th1s_sp3nc3r}
