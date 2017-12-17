# BetterRaw
BetterRaw is a plugin allowing you to [send raw messages to players in any form](#commands-to-send-raw-messages). It also add it's [own custom formating codes](#formating-codes)

## Commands to send raw messages
- /tellraw &lt;player&gt; &lt;message...&gt;    
    This commands sends a raw message in the chat of the targeted player.   
    Note: This is the only command that doesn't support the formatting codes

- /sendtip &lt;player&gt; &lt;message...&gt;    
    This commands sends a message just below to the middle of the screen of the player.

- /sendpopup &lt;player&gt; &lt;message...&gt;    
    This commands sends a message just above the inventory bar of the player

- /sendaction &lt;player&gt; &lt;message...&gt;    
    This commands sends a message on the action bar of the player.

- /sendtitle &lt;player&gt; &lt;title[\\n&lt;subtitle&gt;]...&gt;    
    This commands sends a message in big on the middle of the screen of the player. 
    You can set a subtitle by adding a \\n to your message.

## Formating codes
BetterRaw includes, as in the original form, it's own formatting codes:

<table>
    <tbody>
        <tr>
            <th>Name</th>
            <th>Formatting code</th>
            <th>Description</th>
        </tr>
        <tr>
            <td>Multi color</td>
            <td>§mc</td>
            <td>Text color changes to any color in a defined order</td>
        </tr>
        <tr>
            <td>Gray scale</td>
            <td>§gs</td>
            <td>Text color changes to any gray like color in a defined order</td>
        </tr>
        <tr>
            <td>Left arrows</td>
            <td>§la</td>
            <td>Makes left arrows which forms from 1 arrow to 3 arrows: > to >> to >>></td>
        </tr>
        <tr>
            <td>Right arrows</td>
            <td>§ra</td>
            <td>Makes right arrows which forms from 1 arrow to 3 arrows: < to << to <<<</td>
        </tr>
    </tbody>
</table>
