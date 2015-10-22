#Blender 2: Creating a Humanoid

### What is a humanoid?
A humanoid is a figure that looks like a human or has familiar features of a human

### Modeling the humanoid
![](start.PNG "Fig. 1 The Start Screen")

Press '1' then '5'

![](15.PNG "Fig. 2 Front Ortho View")

Press Tab

![](edit1.PNG "Fig. 3 The Edit Screen")

Press 'A'

![](desel.PNG "Fig. 4 Cube in Edit Mode")

Subdivide the cube

![](subdivide.PNG "Fig. 5 Subdivided cube")

Select half the cube and remove vertices (Toggle "Limit Selection to Visible")

![](half.PNG "Fig. 6 Half of a cube")

Go to modifiers and select the mirror modifier

![](mirror.PNG "Fig. 7 A mirrored cube")

Create the arms by selecting the two top points and extruding

![](extrudearms.PNG "Fig. 8 Extruded arms")

Scale down on the z axis and extrude to add hands and scale up on the z axis

![](scalezaddhands.PNG "Fig. 9 The Torso")

Add legs by extruding. Move the legs apart and extrude to add lower legs

![](AddLegs.PNG "Fig. 10 Headless body")

Rotate the camera to get a side view, select all, scale on the y axis by .5

![](scaley.PNG "Fig. 11 Slimmer body")

Shift + A and add a UV Sphere and place it as a head

![](shiftauvsphere.PNG "Fig. 12 A human-oid")

Add a subdivision surface and smooth out the object with views

![](subsurface.PNG "Fig. 13 The Smoothness")

Press Tab and apply the modifiers

![](ApplyObjectMode.PNG "Fig. 14 The Completed Humanoid")

### Animation

![](xray.PNG "Fig. 15 X-Rayed Humanoid")

Use Z to X-Ray the Model, then use Shift-A to add "Armature -> Single Bone"

![](Armature.PNG "Fig. 16 Adding an Armature")

Press Tab to edit the armature

![](editarmature.PNG "Fig. 17 A Single Bone in Edit Mode")

Select the top and extrude another bone. Continue with the entire body and approximate bones

![](createrough.PNG "Fig. 18 A rough mock-up of bones in the a humanoid")

Tab to object mode and parent the Bones to the Body and hit Ctrl-P and Select Bones -> Automatic Weights

![](objecttoparent.PNG "Fig. 19 Parenting the Body to the Armature")

Enter the Pose Mode and position the camera for Animation

![](poseanimate.PNG "Fig. 20 Ready for Animation/Posing the Armature")

Pose the Humanoid in a resting position and turn on Automatic Keyframe Insertion (Red Record Button)

![](poseautokeyframe.PNG "Fig. 21 Posed Humanoid")

Insert a keyframe at frame 0. Keyframes: Insert -> Whole Character

![](InsertKeyFrame.PNG "Fig. 22 Base Keyframe inserted")

Go 10 seconds into the timeline and start posing the animation. In this example, the left knee will lift and then the right will lift. Insert a Whole Character Keyframe.

![](anim2.PNG "Fig. 23 Posing the Leg")

Go back to the initial Base Keyframe and select copy in the left Pose Tools Menu, go 10 seconds past your latest keyframe and paste the Base Keyframe.

![](copypaste.PNG "Fig. 24 The frame gets reused and allows for proper looping")

Go 10 seconds into the timeline and create a similar animation with the other leg.

![](anim3.PNG "Fig. 25 A second leg movement")

Paste the frame 10 seconds after and crop the animation to 40 frames long.

![](pasteandcrop.PNG "Fig. 26 The final animation")

### Animation Rendering

![](camerarender.PNG "Fig. 27 The camera view")

Adjust the camera so the humanoid is inside the box (Use 0 to select the camera). Change the settings to your liking including specifying a format, location, and quality. Press the Animation button to begin the rendering.

![](Rendering.PNG "Fig. 28 Rendering the video")

![](render.gif "Fig. 29 The GIFed result")

The render result will pop up, switch to 3D view.

![](3dview.PNG "Fig. 30 3D View")

### Further Readings and References
"Blender" - [http://www.blender.org/](http://www.blender.org/)
"Blender Guru" - [http://www.blenderguru.com/](http://www.blenderguru.com/)
