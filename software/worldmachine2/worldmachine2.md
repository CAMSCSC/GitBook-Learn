# World Machine 2
## Texturing a terrain
Terrains are easy to make in WM, but to give them a texture is slightly more difficult.

![](wmstartup.PNG)

Open up WM and remove the terrace and add Erosion

![](erosion.PNG)
Use the Classic WM Erosion setting for best results

![](maps+co.PNG)
Use the [Coastal Overlay Macro](http://www.world-machine.com/library/index.php?entry=47&focus=1) to create a texure. (Note: [This](![](software/worldmachine2/CoastalOverlay.dev) macro has been edited to output many types of maps)

![](co.PNG)
This is what the macro looks like when you enter the macro.

![](final.PNG)

Add Bitmap and Heightmap file outputs. Use PNGs and name according to what it is. (Note: Check off output on every build to quickly create the files by just building)

![](edit.PNG)

Edit all the output files using GIMP.

![](cm.PNG)

The standard Colormap will be the base texture that will be built upon.

![](overlayopacity.PNG)
Select the Light Map Ambient Occlusion and paste it as a new layer. Then, select the Mode as Overlay and the Opacity to taste(50% in this case). Continue doing this with the Ambient Occlusion texture and Flow map.

![](depo.PNG)

For the deposition and wear map, use Addition and a lower opacity.

![](before.PNG)

Untouched from the Coastal Overlay

![](after.PNG)

Edited after using GIMP and the generated maps

## Changing colors with Masks

Use the masks from the Coastal Overlay to replace the colors of the Sand, Grass, or Rock.

![](mask.PNG)
Add the sand mask as a new layer and add a layer mask to it.



