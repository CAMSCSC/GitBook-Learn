# Hello, World!

### What is Hello, World!
Every new programmer knows of this code. Everyone who learned to program somehow has once programmed Hello, World!
Hello, World! is the name of the interlingual program across all programming languages, where all it does is prints "Hello, World!" It is the first program for anyone who is learning a new language, whether he/she is an experienced programmer or a programmer newbie. 

### Saying Hello to many worlds!

**Python**
```python
print("Hello, World!")
```

**C**
```c
main(){
	printf("Hello, World!");
}
```

**C++**
```c++
#include <iostream>

int main()	{
	std::cout << "Hello, World!";
}
```

**C#**
```c#
using System;
class Program
{
    public static void Main(string[] args)
    {
        Console.WriteLine("Hello, world!");
    }
}
```

**Java**
```java
public class HelloWorld {
	public static void main(String[] args) {
		System.out.println("Hello, World!");
	}
}
```

**JavaScript**
```javascript
console.log('Hello, World!');
```

**Windows Batch**
```batch
@echo Hello, World!
```

**Bash (Linux based systems)**
```bash
echo "Hello, World!"
```

**HTML**
```html
<!DOCTYPE html>
<html>
   <head></head>
   <body>
       <p>Hello, world!</p>
   </body>
</html>
```

### Now for some more fun languages...

**BF**
```bf
+++++ +++++             initialize counter (cell #0) to 10
[                       use loop to set the next four cells to 70/100/30/10/40
    > +++++ ++              add  7 to cell #1
    > +++++ +++++           add 10 to cell #2
    > +++                   add  3 to cell #3
    > +                     add  1 to cell #4
    > ++++                  add 4 to cell #5
    <<<<< -                  decrement counter (cell #0)
]
> ++ .                  print 'H'
> + .                   print 'e'
+++++ ++ .              print 'l'
.                       print 'l'
+++ .                   print 'o'
>>> ++++ .              print ','
<< ++ .                 print ' '
 
< +++++ +++ .           print 'w'
 ----- --- .            print 'o'
+++ .                   print 'r'
----- - .               print 'l'
----- --- .             print 'd'
> + .                   print '!'
> .                     print '\n'
```

**Arnold C**
```arnoldc
IT'S SHOWTIME
TALK TO THE HAND "Hello World"
YOU HAVE BEEN TERMINATED
```

**Assembly**
```assembly
.or $300
main    ldy #$00
.1      lda str,y
        beq .2
        jsr $fded ; ROM routine, COUT, y is preserved
        iny
        bne .1
.2      rts
str     .as "HELLO WORLD"
        .hs 0D00
```

**Casio BASIC**
```casiobasic
"HELLO, WORLD!"
```

**LOLCODE**
```lolcode
HAI
CAN HAS STDIO?
VISIBLE "HAI WORLD!"
KTHXBYE
```

**MySQL**
```mysql
DELIMITER $$
CREATE FUNCTION hello_world() RETURNS TEXT COMMENT 'Hello World'
BEGIN
  RETURN 'Hello World';
END;
$$
DELIMITER ;
 
SELECT hello_world();
```

**Visual Basic**
```visualbasic
MsgBox "Hello, world!"
```

***WhiteSpace***
```whitespace
                  
      
                           
      
                               
      
                               
      
                                           
      
                        
      
                  
      
                                           
      
                                           
      
                                 
      
                               
      
                           
      
                        
      
  



```

***Emoticon***
'''emoticon
hello world :-Q S:-P :-Q
'''

### Source
Wikipedia: http://en.wikipedia.org/wiki/List_of_Hello_world_program_examples
