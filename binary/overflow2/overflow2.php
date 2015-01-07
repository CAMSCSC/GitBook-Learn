<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Binary 1: Overflow 2</title>
		<link href="../../styles/prism.css" rel="stylesheet" />
		<link href="../../styles/style.css" rel="stylesheet" />
		<script src="../../scripts/jquery-2.1.1.min.js"></script>
		<script src="../../scripts/lesson.js"></script>
	</head>
	
	<body>
		<h1>Binary 1: Overflow 2</h1>
		
		<h3>A Little Review</h3>
		<p>We learned about the basics of binaries in Overflow 1. If you have not read through Overflow 1 or forgot most of it, I recommend that you review a little before reading on. The important thing we need to remember is that functions vulnerable to overflow such as gets(), memcpy, and strcpy() do not perform boundary checks before executing. They also write data towards the base of the stack (higher memory addresses).<p>
		
		<h3>EIP Register</h3>
		<p>The EIP register or Instruction Pointer is a register that always contains the address of the next instruction to be executed. EIP cannot be modified directly. However, it can be modified indirectly when functions are called or when jumps are made. Notice how EDB puts a green arrow next to memory address specified by the EIP register.</p>
		<figure>
			<img src="001.png" />
			<figcaption>Figure 1: EIP location in ex1</figcaption>
		</figure>
		<p>Because the EIP register points to the NEXT instruction that will be executed, the instruction has not yet been executed. This is why I told you in the previous lesson that you have to refer to the next image to see the propagated changes in the register and memory. While learning about all of the concepts and interactions of EIP register itself is not very interesting, executing arbitrary instructions by exploiting the EIP register can be ;).</p>
		
		<h3>Functions</h3>
		<p>Most programming languages are built on functions. C (and C++/C#) is no exception. Any compiled program is essentially a collection of functions calling each other to perform a certain task. In fact, the main component of a C program is a function called main() if you haven't noticed. To better understand functions, we will create a program that has more than just the main() function.</p>
		<pre class="line-numbers" data-src="ex3.c"></pre>
		<p>The program above does very simple and straightforward things. It prints out "Start" and calls another function name func() while passing it the integer 15. Func() takes the integer and prints it out. Then, the program prints out "End." We need to analyze how the function calls work by looking at the memory and registers in detail.</p>
		<figure>
			<img src="ex3asm.png" />
			<figcaption>Figure 2: Assembly of ex3 shown in EDB</figcaption>
		</figure>
		<p>Notice in figure 2 that the structure of the program stored in the memory is like the file. 0804:844c - 0804:8466 is func() and 0804:8467-0804:8495 is main(). Files are loaded from lower memory addresses to higher memory addresses (which makes more logical sense). The call at 0804:8483 is calling func(), meaning that the mov before is passing it an integer argument of 15. Again, I am not going to continue to pester you with assembly code. Instead, we will look at the memory before and after the function call.</p>
		<figure>
			<img src="funcall.png" />
			<figcaption>Figure 3: Before and After "call func()" in ex3</figcaption>
		</figure>
		<p>The before image was taken right after the execution of 0804:847c. The integer 15 (0F) has just been moved into the memory at ESP. The after image was taken right after a step from 0804:8483 (we would now be in the function func() and the green arrow would point to 0804:844c). Notice that the value pushed into the memory is the address of the instruction after "call 0x08004844c". 08048488 is the address the program is going to jump to after func() completes. We call this the return address. We can generalize the stack appearance after a function call.</p>
		<pre class="language-none" data-src="funcstack.txt"></pre>
		<p>If you are already formulating a plan on how to exploit function returns and calls, you are one step closer to becoming a true hacker :p. However, we need to learn something else before proceeding to exploit the binary.</p>
		
		<h3>From E to G</h3>
		<p>So far, we have been using a graphical debugging tool known as EDB. However, that debugger is not as versatile or as powerful as GDB: the GNU Project debugger. GDB comes with some Linux distributions. We will replicate the disassembly and analysis of ex3 in GDB.</p>
		<pre class="language-null line-numbers" data-src="gdb1"></pre>
		<p>Line 2: Loading the file into GDB.</p>
		<p>Line 4: Setting the output ASM to intel syntax (GDB's default is AT&T).</p>
		<p>Line 5: Disassemble main().</p>
		<p>Line 20: Disassemble func().</p>
		<p>Line 32: Make a breakpoint at 0x0804847c (before copying 15 into memory).</p>
		<p>Line 34: Run the program.</p>
		<p>Line 39: Step into (execute one line of instruction).</p>
		<p>Line 41: View the memory starting at the ESP stack pointer in hex format.</p>
		<p>Line 47: Step into (execute one line of instruction).</p>
		<p>Line 49: View the memory starting at the ESP stack pointer in hex format.</p>
		<p>Do you notice how it is possible to do the same thing with GDB? You might wonder how GDB is more powerful. Primarily, GDB is powerful because it is highly programmable and can interface with Python and other Linux tools. While EDB is good for debugging and assembling programs, GDB is faster and more effective for our purpose. Plus, using it makes you more awesome.</p>
		
		<h3>Mathematically Computing Overflows</h3>
		<p>Let's look at an example of a vulnerable application.</p>
		<pre class="line-numbers" data-src="ex4.c"></pre>
		<p>Our initial reaction might be to use the generalized function call stack to compute the number of padding characters necessary. However, this is not exactly the case. While the program does allocate 100 bytes for the character, it also allocates additional bytes for the function itself. We need to analyze further using GDB.</p>
		<pre class="language-none" data-src="ex4funcasm.txt"></pre>
		<p>Notice that there is a new instruction here called lea. Lea simply calculates an addition or subtraction to memory and moves it into a register. The program allocates 136 bytes in the beginning (0x88 = 136). However, this is not very helpful, as we know that the 100 bytes allocated for the text won't be placed exactly at the top where the ESP is pointing. We can use the lea instruction to determine the location of the buffer instead. Lea in this program is part of the strcpy() function. It is indicating the location for strcpy() to start working. The EBP we know is currently 136 bytes away from ESP. Lea just told us that strcpy() will start copying 108 bytes away from EBP. Lucky us. We now know that the start of the buffer is 28 bytes from ESP. We also know that we are only dealing 108 bytes above the stored EBP value. This makes it easy to apply the general function stack appearance. Starting at the top of the stack, it is 28 bytes to the buffer. The buffer is only 100 bytes meaning that there is an additional 8 bytes we need to fill, making the total padding 108 bytes. To get to argument 1, we also need to overwrite the saved EBP value and return address (segmentation fault here we come). This makes an additional 8 bytes for a total padding of 116 bytes. Finally. We can send 116 bytes of anything and then a 1 to perform this buffer overflow.</p>
		<figure>
			<img src="ex4overflow.png" />
			<figcaption>Figure 4: Performing a buffer overflow on ex4</figcaption>
		</figure>
		<p>Magic. This is pure magic. Let's look at all the Bs in the memory using GDB.</p>
		<pre class="language-none" data-src="ex4gdbmem.txt"></pre>
		<p>If you haven't caught on as to why I use Bs instead of As, it's because B is represented by 42 in hex. But aside from that, I will direct your attention to what we overwrote that is causing the segmentation fault. At 0xbffff46c (last WORD of the 0xbffff460 line), the return address has been replaced by the magnificent 0x42424242. Obviously 0x42424242 is not a valid memory address for this program, so the program basically crashes (and possibly burns). However, what if replace the return address with something that is actually valid? Would the program still execute instructions at the new return address?</p>

		<h3>Messing With the Return</h3>
		<p>Let's look at a modified version of example 4.</p>
		<pre class="line-numbers" data-src="ex5.c"></pre>
		<p>The program works exactly the same as example 4, except there is another function that is never called. First, let's get an output of "Secret!" using the skills that we have learned.</p>
		<pre class="language-none" data-src="ex5gdb.txt"></pre>
		<p>Seems easy enough. Convert 0x28 to decimal and you'll get 40 bytes. Add an additional 8 bytes for the saved EBP value and return address for a total padding of 48 bytes. Let's see if this works.</p>
		<figure>
			<img src="ex5sec.png" />
			<figcaption>Figure 5: Performing a buffer overflow on ex5</figcaption>
		</figure>
		<p>Didn't this just get a whole lot easier once you understand how it works? However, we still received a segmentation fault. In addition, the function that was never called is still not called. What if we wanted both the secret AND the better secret? The solution is very simple, but we need to learn something about a thing called endianness.</p>
		
		<h3>Endianness</h3>
		<p>There are two types of endian formats. Endian formats are simply the order in which data is stored. Big endian stores the most significant byte in the smallest address while little endian stores the least significant byte in the smallest address. The endianness depends completely on the processor and not the operating system. What we need to know is that memory data created by the x86 processor is represented in little endian. Let's look at how strcpy copies things into the memory.</p>
		<pre class="language-none" data-src="endian.txt"></pre>
		<p>Notice how it goes from right to left inside of a WORD? This is why we simply need a "\x01" to change the whole WORD to a 1 rather than "\x00\x00\x00\x01". What this also means is that the bytes need to be reversed when we perform overflows where we are not only using one letter or manipulating one thing.</p>
		
		<h3>Back to Return</h3>
		<p>Let's get back to example 5. We already know the structure of a stack after a function call. The return address is the one right above argument 1. What we want the program to do is to run the lonely() function after finishing func(). To do this, we simply need to modify the return address and point it to where lonely() is in the memory. This shouldn't be too hard any more.</p>
		<pre class="language-none" data-src="lonelyasm.txt"></pre>
		<p>Lonely is located at 0x0804847c. We need to reverse the bytes because of the little endian structure employed by Intel processors. 0x0804847c would become "\x7c\x84\x04\x08". We knew that 48 bytes of padding took us to argument 1. However, we don't want to overwrite the return address with Bs any more. We want to overwrite it with lonely()'s address. Since the saved return address is right before argument 1, we now only need 44 bytes of padding followed by the new memory address and a "\x01" to complete this overflow.</p>
		<figure>
			<img src="betterseg.png" />
			<figcaption>Figure 6: Calling lonely() in ex5 using overflow</figcaption>
		</figure>
		<p>You might wonder why a segmentation fault still occurs. This is still due to the fact that the program is not returning to the main function and exiting properly. The original return address was pointed at the end of main but we've moved it to point to lonely(). After lonely() completes, it returns to an invalid address outside of func().</p>
		
		<h3>Shellcode</h3>
		<p>We know we can perform all of the fancy overflows and stuff but what if we wanted to compromise a whole system by using overflow? To do this, we will need the assistance of a shellcode. A shell code is a minute piece of code (usually no larger than 200 bytes) that generally executes a command shell on the machine. In Linux, it would be bin/sh. However, a shellcode can perform anything the attacker wants because it has the same privilege as the process used to spawn it. <a href="http://shell-storm.org/shellcode/">Shell-storm.org</a> has a list of shellcodes for different operating systems as well as processor architectures. I will use one of them as an example. First, we'll need a program to test it on.</p>
		<pre class="line-numbers" data-src="ex6.c"></pre>
		<p>This program does virtually nothing. However, it is still vulnerable to shellcode. We could try to have the program execute shellcode in the buffer. However, most compilers are smart enough to prevent this from happening. We need to somehow load the shellcode into the memory, find its address, and then execute it. The shell code we are going to use is shown below.</p>
		<pre class="language-none">"\x31\xc0\x89\xc3\xb0\x17\xcd\x80\x31\xd2\x52\x68\x6e\x2f\x73\x68\x68\x2f\x2f\x62\x69\x89\xe3\x52\x53\x89\xe1\x8d\x42\x0b\xcd\x80"</pre>
		<p>It is only 32 bytes and executes a shell. 32 bytes is less than the buffer of 64 bytes so we could easily just put the shell into the buffer and overwrite the return address with starting location of the shell. This is where things start to get really tricky. First, the some parts of the stack are protected from execution. We will bypass this by compiling the program with stack execution enabled.</p>
		<pre class="language-none">gcc -fno-stack-protector -z execstack -o ex6 ex6.c</pre>
		<p>Let's analyze this program using GDB.</p>
		<pre class="language-none" data-src="ex6asm.txt"></pre>
		<p>From breakpoint 2, we discovered that 0xbffff538 is the saved EBP and that 0xb7e75e66 is the return address. This means we have 76 bytes before we need to overwrite the return address. Seems pretty easy. Well, it's not :p. We will now put the shellcode at the beginning and fill the rest with Bs. We'll also replace the return address with Bs just to make sure it works.</p>
		<pre class="language-none" data-src="ex6asm1.txt"></pre>
		<p>Can you spot the difference? ESP was originally pointed to 0xbffff460 when we injected 64 Bs. Now when we add the shellcode, it somehow points to 0xbffff450. This change is caused by the addition of the shellcode and must be taken into account when we point the return address to the shellcode. Size changes of strcpy beyond the buffer can change the ESP value. However, as long as we keep the size the same, the shellcode will still be located at 0xbffff460. We will do the same procedure as before except we will replace the last 4 bytes with the address of the shellcode.</p>
		<pre class="language-none" data-src="ex6asm2.txt"></pre>
		<p>Yes! We have spawned a shell (the # means we have spawned a shell. We can now execute shell commands). So now all we need to do is to execute it in the real non-GDB environment and it should work right? Wrong. 
		<figure>
			<img src="sigill.png" />
			<figcaption>Figure 7: Shellcode failing in real environment</figcaption>
		</figure>
		<p>Why doesn't it work in the real Linux environment? It worked perfectly in GDB. The reason is because GDB's environment is slightly different from the real one. What works in GDB might not work in the actual environment because of slight variations. However, we can counter this and get the shell to work in the real environment as well.</p>
		
		<h3>NOP-sled to the Rescue</h3>
		<p>There is one foundational assembly instruction that I have not yet taught you. I've saved it for this. In assembly, there is an instruction called NOP. NOP does absolutely nothing. It takes up space in the memory but the processor essentially skips it. It even means no operation because literally, there is no operation that this instruction tells the processor to perform. NOP can be represented using the hex character "\x90". How NOP-sled rescue us in the case of overflows and shellcodes? Since the real operating environment differs from the GDB environment, we need a way to make sure that the entire shellcode is execute and no illegal instructions are executed. To do this, we will put our shellcode in between NOPs and Bs. Let's look at the format.</p>
		<pre class="language-none" data-src="ex6sled.txt"></pre>
		<p>What is so good about using NOPs? If we use a NOP-sled, we no longer need to point the return address to the exact location of the shellcode. We simply point it to the middle of the NOP-sled located higher in the stack. This will make sure that the ENTIRE shellcode is executed properly and that no memory violations will occur. However, we only have 76 bytes to mess with. That's not enough. If we had over 100, we could probably use the buffer itself. In this case, we need another way to put more NOPs into the memory before the shellcode. To do this, we can load the shellcode and NOPs into an environmental variable.</p>
		<pre class="language-none">export shell=$(python -c 'print "\x90"*512 + "\x31\xc0\x89\xc3\xb0\x17\xcd\x80\x31\xd2\x52\x68\x6e\x2f\x73\x68\x68\x2f\x2f\x62\x69\x89\xe3\x52\x53\x89\xe1\x8d\x42\x0b\xcd\x80"')</pre>
		<p>I've added 512 bytes before the shellcode so we can adjust the return address later. We need a way to find the memory of the variable. This simple C program will help us.</p>
		<pre data-src="shell.c"></pre>
		<p>To construct our final overflow attack, we need to add shell to the environmental variables (memory). Then, we will run our shell program to find the memory address of shell. Finally, we will run ex6 with 76 Bs and a return address that matches the one outputted by our shell program. If errors occur, we will adjust the value of the return address to higher memory addresses until it works.</p>
		<figure>
			<img src="finalover.png" />
			<figcaption>Figure 8: Constructing and performing the final attack on ex6</figcaption>
		</figure>
		<p>Notice that we had to adjust the return address to a value in the middle of the NOP-sled to get a shell.</p>
		
		<h3>I Dislike Math</h3>
		<p>If you're reading this, you probably like math enough to not die from reading all of that. However, if you're lazy and you just don't want to do math, you can simply load the shellcode into the memory and overflow everything with the return address. The program will do whatever it does and eventually get to the return address.</p>
		<figure>
			<img src="lazy.png" />
			<figcaption>Figure 9: Performing the "lazy" overflow on ex6</figcaption>
		</figure>
		<p>Magic. Pure magic.</p>
	
		<h3>Further Readings and References</h3>
		<p>"Linux Buffer Overflow" - <a href="http://samsclass.info/123/proj14/lbuf1.htm" target="_blank">http://samsclass.info/123/proj14/lbuf1.htm</a></p>
		<p>"GDB Documentation" - <a href="http://www.gnu.org/software/gdb/documentation/" target="_blank">http://www.gnu.org/software/gdb/documentation/</a></p>
		<p>"Shellcode Database" - <a href="http://shell-storm.org/shellcode/" target="_blank">http://shell-storm.org/shellcode/</a></p>
		<p>Tool: Kali Linux - <a href="http://www.kali.org/downloads/" target="_blank">http://www.kali.org/downloads/</a></p>
		<p>Tool: GDB - <a href="http://www.gnu.org/software/gdb/" target="_blank">http://www.gnu.org/software/gdb/</a></p>
		
		<script src="../../scripts/prism.js"></script>
		<?php include('../../footer.php')?>
	</body>
</html>