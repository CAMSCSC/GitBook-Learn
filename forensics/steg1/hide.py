from PIL import Image

def setlsb(component, bit):
	return component & ~1 | int(bit)

def a2bits_list(chars):
	return [bin(ord(x))[2:].rjust(8, '0') for x in chars]

def hide(input_image_file, message):

	img = Image.open(input_image_file)
	encoded = img.copy()
	width, height = img.size
	index = 0

	message = str(len(message)) + ":" + message
	message_bits = "".join(a2bits_list(message))

	npixels = width * height
	if len(message_bits) > npixels * 3:
		raise Exception("""The message you want to hide is too long (%s > %s).""" % (len(message_bits), npixels * 3))

	for row in range(height):
		for col in range(width):

			if index + 3 <= len(message_bits) :

				(r, g, b) = img.getpixel((col, row))

				r = setlsb(r, message_bits[index])
				g = setlsb(g, message_bits[index+1])
				b = setlsb(b, message_bits[index+2])

				encoded.putpixel((col, row), (r, g , b))

			index += 3

	return encoded

if __name__ == "__main__":
	steg = hide('carrier.png', 'The secret message.')
	steg.save('output.png')