<?php

function pre($data, $c=false)
{
    if($c)
    {
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $output->writeln("<info>".var_dump($data)."</info>");
    }
    else
    {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
    }
  
}